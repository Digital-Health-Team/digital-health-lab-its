<?php

use App\Actions\Chatbot\BuildKnowledgeIndexAction;
use App\Models\KnowledgeChunk;
use App\Models\Service;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->knowledgePath = storage_path('framework/testing/knowledge-'.uniqid());

    File::ensureDirectoryExists("{$this->knowledgePath}/id");
    File::ensureDirectoryExists("{$this->knowledgePath}/en");

    config([
        'gemini.api_key' => 'test-key',
        'gemini.base_url' => 'https://example.test/v1beta',
        'gemini.embedding_model' => 'test-embedding-model',
        'gemini.embedding_dimensions' => 4,
        'gemini.knowledge_path' => $this->knowledgePath,
        // Real ingest paces itself to stay inside the free-tier RPM; the tests must not.
        'gemini.ingest_delay_ms' => 0,
    ]);

    // Resolved per request through a swappable slot: Http::fake() appends stubs and the
    // first match wins, so a test could not otherwise override a stub set here.
    $this->embed = fn () => Http::response(['embedding' => ['values' => [1.0, 0.0, 0.0, 0.0]]]);

    Http::fake(['*:embedContent' => fn () => ($this->embed)()]);
});

afterEach(function () {
    File::deleteDirectory($this->knowledgePath);
});

function writeKnowledge(string $locale, string $filename, string $contents): void
{
    File::put(test()->knowledgePath."/{$locale}/{$filename}", $contents);
}

function indexMarkdown(bool $fresh = false): array
{
    return app(BuildKnowledgeIndexAction::class)->execute(['md'], $fresh);
}

it('parses front matter including a flow sequence wrapped over several lines', function () {
    writeKnowledge('id', 'faq-umum.md', <<<'MD'
        ---
        id: faq-umum
        judul: Pertanyaan yang Sering Diajukan
        kategori: faq
        audience: public
        url: /services
        kata_kunci:
            [
                cetak 3d,
                print 3d,
                ngeprint,
            ]
        terakhir_ditinjau: 2026-07-30
        ---

        ## Berapa lama proses cetak 3D

        Pencetakan objek kecil selesai dalam dua hari kerja.
        MD);

    indexMarkdown();

    $chunk = KnowledgeChunk::query()->sole();

    expect($chunk->source_key)->toBe('md:id:faq-umum')
        ->and($chunk->title)->toBe('Pertanyaan yang Sering Diajukan')
        ->and($chunk->category)->toBe('faq')
        ->and($chunk->audience)->toBe('public')
        ->and($chunk->url)->toBe('/services')
        ->and($chunk->locale)->toBe('id')
        ->and($chunk->dimensions)->toBe(4);

    // kata_kunci is embedded but never stored in the visible content.
    Http::assertSent(fn ($request) => str_contains(
        $request['content']['parts'][0]['text'],
        'cetak 3d, print 3d, ngeprint',
    ));
});

it('rejects a document whose front matter is missing a required key', function () {
    writeKnowledge('id', 'rusak.md', <<<'MD'
        ---
        id: rusak
        judul: Tanpa Kategori
        audience: public
        ---

        ## Sesuatu

        Isi.
        MD);

    expect(fn () => indexMarkdown())
        ->toThrow(RuntimeException::class, 'knowledge/rusak.md: front matter is missing `kategori`.');
});

it('makes one chunk per level-two heading and keeps the preamble', function () {
    writeKnowledge('id', 'prosedur.md', <<<'MD'
        ---
        id: prosedur
        judul: Prosedur Pemesanan
        kategori: layanan
        audience: public
        ---

        Halaman ini menjelaskan alur memesan cetak 3D dari berkas sampai barang jadi.

        ## Siapa yang boleh memesan

        Layanan terbuka untuk mahasiswa dan masyarakat umum.

        ## Cara memantau progres

        Progres pesanan bisa dilihat di dashboard pada halaman Pesanan.
        MD);

    indexMarkdown();

    $chunks = KnowledgeChunk::query()->orderBy('id')->get();

    expect($chunks)->toHaveCount(3)
        ->and($chunks[0]->heading)->toBeNull()
        ->and($chunks[0]->content)->toContain('alur memesan cetak 3D')
        ->and($chunks[1]->heading)->toBe('Siapa yang boleh memesan')
        ->and($chunks[2]->heading)->toBe('Cara memantau progres');

    // The title travels with every chunk — without it "progres bisa dilihat di dashboard"
    // cannot be told apart from the same sentence in another document.
    Http::assertSent(fn ($request) => str_starts_with(
        $request['content']['parts'][0]['text'],
        'Prosedur Pemesanan — Cara memantau progres',
    ));
});

it('splits a section that runs past 1500 characters', function () {
    $paragraph = str_repeat('Biaya cetak 3D dihitung per gram bahan yang terpakai. ', 12); // ~624 chars

    writeKnowledge('id', 'panjang.md', <<<MD
        ---
        id: panjang
        judul: Dokumen Panjang
        kategori: kebijakan
        audience: public
        ---

        ## Bagian yang terlalu panjang

        {$paragraph}

        {$paragraph}

        {$paragraph}
        MD);

    indexMarkdown();

    $chunks = KnowledgeChunk::query()->orderBy('id')->get();

    expect($chunks->count())->toBeGreaterThan(1)
        ->and($chunks->first()->heading)->toBe('Bagian yang terlalu panjang')
        ->and($chunks->last()->heading)->toBe('Bagian yang terlalu panjang (2)');

    $chunks->each(fn (KnowledgeChunk $chunk) => expect(mb_strlen($chunk->content))->toBeLessThanOrEqual(1500));
});

it('skips an unchanged document without spending an embedding call', function () {
    writeKnowledge('id', 'kontak.md', <<<'MD'
        ---
        id: kontak
        judul: Kontak dan Jam Operasional
        kategori: profil
        audience: public
        ---

        ## Jam operasional lab

        Lab buka Senin sampai Jumat pukul delapan pagi hingga empat sore.
        MD);

    expect(indexMarkdown())->toMatchArray(['indexed' => 1, 'skipped' => 0, 'chunks' => 1]);

    Http::assertSentCount(1);

    // This is what keeps a daily reindex free: nothing changed, so nothing is embedded.
    expect(indexMarkdown())->toMatchArray(['indexed' => 0, 'skipped' => 1, 'chunks' => 0]);

    Http::assertSentCount(1);
});

it('re-embeds a document after its content changes', function () {
    $frontmatter = <<<'MD'
        ---
        id: kebijakan
        judul: Kebijakan Pembayaran
        kategori: kebijakan
        audience: public
        ---

        ## Uang muka

        MD;

    writeKnowledge('id', 'kebijakan.md', $frontmatter.'Uang muka sebesar tiga puluh persen dibayar sebelum pencetakan.');
    indexMarkdown();

    writeKnowledge('id', 'kebijakan.md', $frontmatter.'Uang muka sebesar empat puluh persen dibayar sebelum pencetakan.');

    expect(indexMarkdown())->toMatchArray(['indexed' => 1, 'skipped' => 0])
        ->and(KnowledgeChunk::query()->sole()->content)->toContain('empat puluh persen');
});

it('writes nothing when embedding fails part-way through a document', function () {
    writeKnowledge('id', 'kebijakan.md', <<<'MD'
        ---
        id: kebijakan
        judul: Kebijakan Pembayaran
        kategori: kebijakan
        audience: public
        ---

        ## Uang muka

        Uang muka sebesar tiga puluh persen dibayar sebelum pencetakan dimulai.

        ## Pelunasan

        Sisa pembayaran dilunasi setelah proses finishing selesai.

        ## Pembatalan

        Pembatalan masih mungkin sebelum pencetakan dimulai.
        MD);

    // Succeed once, then hit the quota — the shape of a real 429 mid-ingest.
    $calls = 0;
    $this->embed = function () use (&$calls) {
        $calls++;

        return $calls === 1
            ? Http::response(['embedding' => ['values' => [1.0, 0.0, 0.0, 0.0]]])
            : Http::response(['error' => ['message' => 'quota exceeded']], 429);
    };

    expect(fn () => indexMarkdown())->toThrow(RuntimeException::class, 'quota exceeded');

    // Nothing written. A partial write would carry the new source_hash, so the next run
    // would treat the document as unchanged and leave it permanently short of two sections.
    expect(KnowledgeChunk::query()->count())->toBe(0);

    $this->embed = fn () => Http::response(['embedding' => ['values' => [1.0, 0.0, 0.0, 0.0]]]);

    expect(indexMarkdown())->toMatchArray(['indexed' => 1, 'skipped' => 0, 'chunks' => 3])
        ->and(KnowledgeChunk::query()->count())->toBe(3);
});

it('keeps the previous chunks when a re-index fails', function () {
    $frontmatter = "---\nid: kontak\njudul: Kontak\nkategori: profil\naudience: public\n---\n\n## Jam operasional\n\n";

    writeKnowledge('id', 'kontak.md', $frontmatter.'Lab buka Senin sampai Jumat pukul delapan pagi.');
    indexMarkdown();

    writeKnowledge('id', 'kontak.md', $frontmatter.'Lab buka Senin sampai Sabtu pukul tujuh pagi.');

    $this->embed = fn () => Http::response(['error' => ['message' => 'quota exceeded']], 429);

    expect(fn () => indexMarkdown())->toThrow(RuntimeException::class);

    // A failed refresh must leave the old answer standing, not delete it.
    expect(KnowledgeChunk::query()->sole()->content)->toContain('Senin sampai Jumat');
});

it('prunes chunks whose source document is gone', function () {
    writeKnowledge('id', 'sementara.md', <<<'MD'
        ---
        id: sementara
        judul: Dokumen Sementara
        kategori: faq
        audience: public
        ---

        ## Sesuatu

        Isi yang nanti dihapus dari knowledge base.
        MD);

    indexMarkdown();
    expect(KnowledgeChunk::query()->count())->toBe(1);

    File::delete("{$this->knowledgePath}/id/sementara.md");

    expect(indexMarkdown())->toMatchArray(['pruned' => 1])
        ->and(KnowledgeChunk::query()->count())->toBe(0);
});

it('indexes each locale separately', function () {
    $body = <<<'MD'

        ## Jam operasional lab

        Lab buka Senin sampai Jumat.
        MD;

    writeKnowledge('id', 'kontak.md', "---\nid: kontak\njudul: Kontak\nkategori: profil\naudience: public\n---\n".$body);
    writeKnowledge('en', 'kontak.md', "---\nid: kontak\njudul: Contact\nkategori: profil\naudience: public\n---\n".$body);

    indexMarkdown();

    expect(KnowledgeChunk::query()->pluck('source_key')->sort()->values()->all())
        ->toBe(['md:en:kontak', 'md:id:kontak']);
});

it('builds a bilingual service document from the database', function () {
    Service::create([
        'name' => 'Cetak 3D',
        'name_en' => '3D Printing',
        'service_type' => 'printing',
        'description' => 'Layanan pencetakan tiga dimensi.',
        'description_en' => 'Three dimensional printing service.',
        'base_price' => 50000,
        'whatsapp_number' => '628123456789',
    ]);

    app(BuildKnowledgeIndexAction::class)->execute(['service']);

    $indonesian = KnowledgeChunk::query()->where('locale', 'id')->get();
    $english = KnowledgeChunk::query()->where('locale', 'en')->get();

    expect($indonesian->pluck('title')->unique()->all())->toBe(['Cetak 3D'])
        ->and($english->pluck('title')->unique()->all())->toBe(['3D Printing'])
        ->and($indonesian->pluck('heading')->all())->toContain('Biaya layanan Cetak 3D')
        ->and($english->pluck('heading')->all())->toContain('Cost of the 3D Printing service')
        ->and($indonesian->firstWhere('heading', 'Biaya layanan Cetak 3D')->content)
        ->toContain('Rp 50.000')
        ->and($indonesian->first()->url)
        ->toBe(route('services.show', Service::query()->sole()->id, absolute: false));
});

it('stores relative source urls, never absolute ones', function () {
    // The index is built by an artisan command, where route() resolves against APP_URL. With
    // APP_URL still at its default this baked `http://localhost/services/1` into every chunk
    // and the chatbot handed that to real visitors. Relative paths work on any host.
    config(['app.url' => 'http://localhost']);

    Service::create([
        'name' => 'Cetak 3D',
        'service_type' => 'printing',
        'description' => 'Layanan pencetakan tiga dimensi.',
        'base_price' => 50000,
    ]);

    app(BuildKnowledgeIndexAction::class)->execute(['service']);

    $urls = KnowledgeChunk::query()->pluck('url')->unique();

    expect($urls)->not->toBeEmpty()
        ->and($urls->filter(fn (?string $url): bool => str_starts_with((string) $url, 'http')))->toBeEmpty()
        ->and($urls->every(fn (?string $url): bool => str_starts_with((string) $url, '/')))->toBeTrue();
});

it('leaves the active locale untouched after indexing database sources', function () {
    Service::create([
        'name' => 'Scan 3D',
        'service_type' => 'scanning',
        'description' => 'Layanan pemindaian tiga dimensi.',
        'base_price' => 75000,
    ]);

    app()->setLocale('id');

    app(BuildKnowledgeIndexAction::class)->execute(['service']);

    expect(app()->getLocale())->toBe('id');
});

it('rejects an unknown source name', function () {
    expect(fn () => app(BuildKnowledgeIndexAction::class)->execute(['nonsense']))
        ->toThrow(RuntimeException::class, 'No known source selected');
});
