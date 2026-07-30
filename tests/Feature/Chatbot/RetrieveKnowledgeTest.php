<?php

use App\Actions\Chatbot\RetrieveKnowledgeAction;
use App\Models\KnowledgeChunk;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'gemini.api_key' => 'test-key',
        'gemini.base_url' => 'https://example.test/v1beta',
        'gemini.embedding_model' => 'test-embedding-model',
        'gemini.embedding_dimensions' => 4,
        'gemini.retrieval.top_k' => 5,
        'gemini.retrieval.min_score' => 0.55,
        'gemini.retrieval.cache_ttl' => 300,
    ]);

    // Every chunk vector below is unit length, so a dot product against this query is an
    // exact, hand-checkable cosine similarity.
    Http::fake([
        '*:embedContent' => Http::response(['embedding' => ['values' => [1.0, 0.0, 0.0, 0.0]]]),
    ]);
});

/** @param  array<int, float>  $vector */
function chunk(array $vector, array $attributes = []): KnowledgeChunk
{
    return KnowledgeChunk::create([
        'source_key' => $attributes['source_key'] ?? 'md:id:'.uniqid(),
        'source_hash' => str_repeat('a', 64),
        'locale' => $attributes['locale'] ?? 'id',
        'audience' => $attributes['audience'] ?? 'public',
        'category' => $attributes['category'] ?? 'faq',
        'title' => $attributes['title'] ?? 'Judul',
        'heading' => $attributes['heading'] ?? 'Heading',
        'content' => $attributes['content'] ?? 'Isi potongan.',
        'url' => $attributes['url'] ?? '/services',
        'embedding' => pack('g*', ...$vector),
        'dimensions' => count($vector),
    ]);
}

function retrieve(string $locale = 'id', bool $authenticated = false): array
{
    return app(RetrieveKnowledgeAction::class)->execute('berapa harga cetak 3d', $locale, $authenticated);
}

it('embeds the question as a query, not as a document', function () {
    chunk([1.0, 0.0, 0.0, 0.0]);

    retrieve();

    // RETRIEVAL_DOCUMENT here would silently degrade ranking: the model embeds questions
    // and passages asymmetrically.
    Http::assertSent(fn ($request) => $request['taskType'] === 'RETRIEVAL_QUERY');
});

it('ranks by similarity and drops everything under the threshold', function () {
    chunk([0.8, 0.6, 0.0, 0.0], ['heading' => 'Cukup mirip']);   // score 0.8
    chunk([1.0, 0.0, 0.0, 0.0], ['heading' => 'Paling mirip']);  // score 1.0
    chunk([0.0, 1.0, 0.0, 0.0], ['heading' => 'Tidak mirip']);   // score 0.0

    $result = retrieve();

    expect(collect($result['chunks'])->pluck('heading')->all())->toBe(['Paling mirip', 'Cukup mirip'])
        ->and($result['top_score'])->toEqual(1.0)
        ->and($result['fallback'])->toBeFalse()
        ->and($result['locale'])->toBe('id');
});

it('returns nothing when every chunk sits below the threshold', function () {
    chunk([0.5, 0.866025, 0.0, 0.0]); // score 0.5, just under 0.55

    $result = retrieve();

    expect($result['chunks'])->toBe([])
        ->and($result['top_score'])->toEqual(0.0);
});

it('honours a raised threshold', function () {
    config(['gemini.retrieval.min_score' => 0.95]);

    chunk([0.8, 0.6, 0.0, 0.0]);

    expect(retrieve()['chunks'])->toBe([]);
});

it('caps the result at top_k', function () {
    config(['gemini.retrieval.top_k' => 2]);

    foreach (range(1, 5) as $i) {
        chunk([1.0, 0.0, 0.0, 0.0], ['heading' => "Potongan {$i}"]);
    }

    expect(retrieve()['chunks'])->toHaveCount(2);
});

it('never returns chunks from another locale', function () {
    chunk([1.0, 0.0, 0.0, 0.0], ['locale' => 'id', 'heading' => 'Versi Indonesia']);
    chunk([0.9, 0.435889, 0.0, 0.0], ['locale' => 'en', 'heading' => 'English version']);

    $indonesian = retrieve('id');
    $english = retrieve('en');

    // Mixed-language context makes the model answer in mixed language and links point at
    // the wrong language version, so the filter is absolute — not a ranking preference.
    expect(collect($indonesian['chunks'])->pluck('heading')->all())->toBe(['Versi Indonesia'])
        ->and(collect($english['chunks'])->pluck('heading')->all())->toBe(['English version'])
        ->and($english['fallback'])->toBeFalse();
});

it('keeps authenticated chunks out of public mode', function () {
    chunk([1.0, 0.0, 0.0, 0.0], ['audience' => 'authenticated', 'heading' => 'Hanya untuk yang login']);
    chunk([0.9, 0.435889, 0.0, 0.0], ['audience' => 'public', 'heading' => 'Untuk semua']);

    expect(collect(retrieve('id', false)['chunks'])->pluck('heading')->all())
        ->toBe(['Untuk semua']);
});

it('includes authenticated chunks for a signed-in asker', function () {
    chunk([1.0, 0.0, 0.0, 0.0], ['audience' => 'authenticated', 'heading' => 'Hanya untuk yang login']);
    chunk([0.9, 0.435889, 0.0, 0.0], ['audience' => 'public', 'heading' => 'Untuk semua']);

    expect(collect(retrieve('id', true)['chunks'])->pluck('heading')->all())
        ->toBe(['Hanya untuk yang login', 'Untuk semua']);
});

it('falls back from en to id and flags it', function () {
    chunk([1.0, 0.0, 0.0, 0.0], ['locale' => 'id', 'heading' => 'Biaya cetak 3D']);

    $result = retrieve('en');

    expect(collect($result['chunks'])->pluck('heading')->all())->toBe(['Biaya cetak 3D'])
        ->and($result['locale'])->toBe('id')
        ->and($result['fallback'])->toBeTrue();
});

it('does not fall back when the English index already answers', function () {
    chunk([1.0, 0.0, 0.0, 0.0], ['locale' => 'en', 'heading' => '3D printing cost']);
    chunk([1.0, 0.0, 0.0, 0.0], ['locale' => 'id', 'heading' => 'Biaya cetak 3D']);

    $result = retrieve('en');

    expect(collect($result['chunks'])->pluck('heading')->all())->toBe(['3D printing cost'])
        ->and($result['fallback'])->toBeFalse();
});

it('does not fall back from id to en', function () {
    chunk([1.0, 0.0, 0.0, 0.0], ['locale' => 'en', 'heading' => '3D printing cost']);

    $result = retrieve('id');

    expect($result['chunks'])->toBe([])
        ->and($result['fallback'])->toBeFalse()
        ->and($result['locale'])->toBe('id');
});

it('reports nothing found when the index is empty, without falling back', function () {
    $result = retrieve('en');

    expect($result['chunks'])->toBe([])
        ->and($result['top_score'])->toEqual(0.0)
        ->and($result['fallback'])->toBeFalse();
});

it('skips chunks left at a different embedding width', function () {
    chunk([1.0, 0.0, 0.0, 0.0, 0.0, 0.0], ['heading' => 'Dimensi lama']);
    chunk([1.0, 0.0, 0.0, 0.0], ['heading' => 'Dimensi sekarang']);

    // A stale-width vector must be ignored, not compared — comparing across widths yields
    // a confident but meaningless score. `chatbot:index --fresh` is the real fix.
    expect(collect(retrieve()['chunks'])->pluck('heading')->all())->toBe(['Dimensi sekarang']);
});

it('does not call the API for a blank question', function () {
    chunk([1.0, 0.0, 0.0, 0.0]);

    $result = app(RetrieveKnowledgeAction::class)->execute('   ', 'id');

    expect($result['chunks'])->toBe([]);
    Http::assertNothingSent();
});

it('serves repeat questions from the cache', function () {
    chunk([1.0, 0.0, 0.0, 0.0]);

    retrieve();
    KnowledgeChunk::query()->delete();

    // Still answered from the cached, unpacked list — this is what keeps a full scan cheap.
    expect(retrieve()['chunks'])->toHaveCount(1);
});

it('flushes every cached locale and audience list', function () {
    chunk([1.0, 0.0, 0.0, 0.0], ['locale' => 'id']);
    chunk([1.0, 0.0, 0.0, 0.0], ['locale' => 'en']);

    retrieve('id', false);
    retrieve('id', true);
    retrieve('en', false);

    RetrieveKnowledgeAction::flushCache();

    foreach (KnowledgeChunk::LOCALES as $locale) {
        foreach (['public', 'auth'] as $scope) {
            expect(Cache::has(KnowledgeChunk::CACHE_KEY.":{$locale}:{$scope}"))->toBeFalse();
        }
    }
});

it('is flushed by a reindex so fresh chunks are visible immediately', function () {
    chunk([1.0, 0.0, 0.0, 0.0], ['heading' => 'Lama']);
    retrieve();

    chunk([1.0, 0.0, 0.0, 0.0], ['heading' => 'Baru']);
    expect(retrieve()['chunks'])->toHaveCount(1); // still the cached list

    RetrieveKnowledgeAction::flushCache();

    expect(retrieve()['chunks'])->toHaveCount(2);
});
