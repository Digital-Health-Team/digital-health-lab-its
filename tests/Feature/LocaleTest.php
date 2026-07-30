<?php

use App\Http\Controllers\NewsController;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Inertia\Testing\AssertableInertia as Assert;

/** Every `"key":` at the start of a line, escaped inner quotes included. */
function translationKeyList(string $file): array
{
    preg_match_all('/^\s*"((?:[^"\\\\]|\\\\.)*)"\s*:/m', File::get(lang_path($file)), $matches);

    return array_map(fn (string $key) => stripcslashes($key), $matches[1]);
}

function frontendSource(string $relative): string
{
    return File::get(resource_path("js/{$relative}"));
}

/** The events hero copy, which EventsHero renders through t(). */
function heroCopy(): array
{
    preg_match_all(
        '/^\s+(?:title|subtitle|ctaLabel):\s*\n?\s*"((?:[^"\\\\]|\\\\.)*)"/m',
        frontendSource('Features/Events/Pages/EventsPage.tsx'),
        $matches
    );

    return $matches[1];
}

/** The dashboard catalogue tile labels, rendered through t() by CategoryTile. */
function catalogueLabels(): array
{
    preg_match_all(
        '/^\s+label:\s*"((?:[^"\\\\]|\\\\.)*)"/m',
        frontendSource('Features/Dashboard/Data/categories.data.ts'),
        $matches
    );

    return $matches[1];
}

/** The three status words the badges and the catalogue filter tabs share. */
function eventStatusLabels(): array
{
    // Anchored on the export name: statusStyles and onImageStyles are keyed by the
    // same three statuses and would otherwise contribute Tailwind class strings.
    preg_match(
        '/export const statusLabels[^{]*\{(.*?)\}/s',
        frontendSource('Features/Events/Components/EventCatalogue/fragments/EventStatusBadge.tsx'),
        $block
    );

    preg_match_all('/:\s*"((?:[^"\\\\]|\\\\.)*)"/', $block[1] ?? '', $matches);

    return $matches[1];
}

test('guests default to indonesian', function () {
    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('locale', 'id'));
});

test('switching locale persists in the session', function () {
    $this->from('/')->post('/locale', ['locale' => 'en'])->assertRedirect('/');

    expect(session('locale'))->toBe('en');

    $this->get('/')->assertInertia(fn (Assert $page) => $page->where('locale', 'en'));
});

test('switching locale persists on the authenticated user', function () {
    $user = User::factory()->create(['locale' => 'id']);

    $this->actingAs($user)->from('/')->post('/locale', ['locale' => 'en']);

    expect($user->fresh()->locale)->toBe('en');
});

test('an unsupported locale is rejected', function () {
    $this->from('/')->post('/locale', ['locale' => 'fr'])
        ->assertSessionHasErrors('locale');

    expect(session('locale'))->toBeNull();
});

test('the translation map is shared for indonesian but empty for the fallback locale', function () {
    // Keys ARE the English source strings, so `en` needs no payload — t() returns the key.
    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('locale', 'id')
        ->where('translations.Home', 'Beranda')
    );

    $this->post('/locale', ['locale' => 'en']);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('locale', 'en')
        ->where('translations', [])
    );
});

test('a user preferred locale drives notifications and requests', function () {
    $user = User::factory()->create(['locale' => 'en']);

    expect($user->preferredLocale())->toBe('en');

    $this->actingAs($user)->get('/')
        ->assertInertia(fn (Assert $page) => $page->where('locale', 'en'));
});

test('validation errors are translated to indonesian', function () {
    app()->setLocale('id');

    expect(__('validation.required', ['attribute' => 'email']))
        ->not->toBe('validation.required')
        ->not->toContain('field is required');
});

test('the translation files carry no duplicate keys', function () {
    // json_decode keeps only the last value for a repeated key, so a duplicate
    // silently overrides the earlier translation with no error anywhere.
    foreach (['id.json', 'en.json'] as $file) {
        $keys = translationKeyList($file);
        $duplicates = array_keys(array_filter(array_count_values($keys), fn (int $n) => $n > 1));

        expect($keys)->not->toBeEmpty();
        expect($duplicates)->toBe([], "duplicate keys in lang/{$file}: ".implode(', ', $duplicates));
    }
});

test('every string on the events page and dashboard catalogue is translated', function () {
    // t() keys ARE the English source string and fall back to the key, so a key
    // missing from id.json renders English — and an Indonesian source string
    // renders Indonesian even in `en`. Both show up as a miss here.
    $translations = File::json(lang_path('id.json'));

    $files = collect(File::allFiles(resource_path('js/Features/Events')))
        ->merge(File::allFiles(resource_path('js/Features/Dashboard/Components/CategoryQuickAccess')))
        ->filter(fn ($file) => $file->getExtension() === 'tsx');

    $keys = $files->flatMap(function ($file) {
        preg_match_all('/\bt\(\s*"((?:[^"\\\\]|\\\\.)*)"\s*\)/', $file->getContents(), $matches);

        return $matches[1];
    })
        // Copy that reaches t() from a data file rather than a literal call site.
        // Read out of the sources so a reverted string fails here, not in review.
        ->merge(heroCopy())
        ->merge(catalogueLabels())
        ->merge(eventStatusLabels())
        ->push('All')                 // the catalogue's one non-status filter tab
        ->unique();

    // Pin the scraped shapes so a regex that silently stops matching fails loudly
    // rather than passing an empty set.
    expect(heroCopy())->toHaveCount(3);          // title, subtitle, ctaLabel
    expect(catalogueLabels())->toHaveCount(7);   // the seven core competencies
    expect(eventStatusLabels())->toHaveCount(3); // ongoing, upcoming, past
    expect($keys->count())->toBeGreaterThan(30);

    expect($keys->reject(fn (string $key) => isset($translations[$key]))->values()->all())->toBe([]);
});

test('no t() key anywhere in the frontend is authored in indonesian', function () {
    // The guard the test above cannot provide. `t()` returns the key unchanged for
    // `en`, so an Indonesian key renders Indonesian in English — and if that same
    // Indonesian string also happens to be present in id.json, the "is it
    // translated" check above passes while the English page stays broken.
    //
    // Common function words only. Anything matching is a source string that was
    // written in Indonesian instead of English.
    $stopwords = [
        'dan', 'yang', 'untuk', 'dengan', 'atau', 'tidak', 'adalah', 'akan', 'pada',
        'anda', 'kami', 'kita', 'ini', 'itu', 'dari', 'ke', 'di', 'sudah', 'belum',
        'semua', 'lihat', 'kabar', 'selengkapnya', 'kembali', 'unggah', 'unduh',
        'kirim', 'pilih', 'klik', 'beranda', 'silakan', 'terkirim', 'menyimpan',
        'simpan', 'batal', 'tutup', 'hari', 'jam', 'menit', 'detik', 'bukti',
        'pembayaran', 'keahlian', 'pendidikan', 'proyek', 'karya', 'kegiatan',
    ];
    $pattern = '/\b('.implode('|', $stopwords).')\b/iu';

    $scraped = collect(File::allFiles(resource_path('js')))
        ->filter(fn ($file) => in_array($file->getExtension(), ['ts', 'tsx'], true))
        ->flatMap(function ($file) {
            preg_match_all('/\bt\(\s*"((?:[^"\\\\]|\\\\.)*)"\s*\)/', $file->getContents(), $matches);

            return collect($matches[1])->map(fn (string $key) => [
                'file' => $file->getRelativePathname(),
                'key' => $key,
            ]);
        });

    // Pin the scrape so a regex that silently stops matching fails loudly here
    // instead of reporting a clean sweep over zero strings.
    expect($scraped->count())->toBeGreaterThan(250);

    $offenders = $scraped
        ->filter(fn (array $hit) => preg_match($pattern, $hit['key']) === 1)
        ->map(fn (array $hit) => "{$hit['file']}: \"{$hit['key']}\"")
        ->values();

    expect($offenders->all())->toBe([]);
});

test('no __() key in php is authored in indonesian', function () {
    // Same bug class as the t() guard above, on the Blade/PHP side: __() returns the
    // key unchanged for `en`, so an Indonesian key renders Indonesian in English.
    // This is how `__('Bukti pembayaran berhasil diunggah…')` slipped through review.
    //
    // Scoped to the code paths that render user-facing copy. `lang/` is excluded —
    // the Indonesian there is the translation, which is the whole point.
    //
    // `resources/views/livewire/admin` is excluded too: the admin interface is
    // Indonesian-first for Indonesian lab staff, and it still holds 22 Indonesian
    // __() keys (mostly the landing-content CMS form hints). Translating those is a
    // separate decision about whether the admin UI should be bilingual at all.
    $stopwords = [
        'dan', 'yang', 'untuk', 'dengan', 'atau', 'tidak', 'adalah', 'akan',
        'anda', 'kami', 'ini', 'itu', 'dari', 'sudah', 'belum', 'berhasil',
        'silakan', 'menunggu', 'diunggah', 'diperbarui', 'dihapus', 'dibuat',
        'bukti', 'pembayaran', 'pesanan', 'profil', 'proyek', 'kembali',
    ];
    $pattern = '/\b('.implode('|', $stopwords).')\b/iu';

    $roots = [app_path(), resource_path('views'), database_path('seeders')];

    $scraped = collect($roots)
        ->flatMap(fn (string $dir) => File::allFiles($dir))
        ->filter(fn ($file) => $file->getExtension() === 'php')
        ->reject(fn ($file) => str_contains($file->getPathname(), '/livewire/admin/'))
        ->flatMap(function ($file) {
            // __('…') and __("…"), single- or double-quoted, escapes included.
            preg_match_all(
                '/\b__\(\s*(?:\'((?:[^\'\\\\]|\\\\.)*)\'|"((?:[^"\\\\]|\\\\.)*)")/',
                $file->getContents(),
                $matches
            );

            return collect($matches[1])
                ->zip($matches[2])
                ->map(fn ($pair) => ['file' => $file->getFilename(), 'key' => $pair[0] ?: $pair[1]])
                ->filter(fn (array $hit) => $hit['key'] !== '');
        });

    // Pin the scrape so a broken regex cannot report a clean sweep over nothing.
    expect($scraped->count())->toBeGreaterThan(200);

    $offenders = $scraped
        // Dotted keys resolve through lang/id/*.php, where Indonesian is correct.
        ->reject(fn (array $hit) => preg_match('/^[a-z_]+\.[a-z_.]+$/', $hit['key']) === 1)
        ->filter(fn (array $hit) => preg_match($pattern, $hit['key']) === 1)
        ->map(fn (array $hit) => "{$hit['file']}: \"{$hit['key']}\"")
        ->unique()
        ->values();

    expect($offenders->all())->toBe([]);
});

test('an english overlay column is used for en and falls back to indonesian', function () {
    $service = Service::create([
        'name' => 'Jasa Uji Coba',
        'name_en' => 'Trial Service',
        'service_type' => 'printing',
        'description' => 'Deskripsi bahasa Indonesia.',
        'base_price' => 1000,
    ]);

    app()->setLocale('id');
    expect($service->localized('name'))->toBe('Jasa Uji Coba');

    app()->setLocale('en');
    expect($service->localized('name'))->toBe('Trial Service');

    // A row with no English copy must degrade to Indonesian, not render blank.
    expect($service->localized('description'))->toBe('Deskripsi bahasa Indonesia.');

    // The raw attribute must stay the stored base column — the admin CRUD forms load
    // `$model->name` and write it straight back, so a localised read here would
    // overwrite the Indonesian copy with the English one on save.
    expect($service->name)->toBe('Jasa Uji Coba');

    expect(fn () => $service->localized('base_price'))
        ->toThrow(InvalidArgumentException::class);
});

test('every lab-news article carries english copy', function () {
    // NewsController::localised() falls back to Indonesian, so a missing `_en` is
    // silent — /news would just quietly stay Indonesian for English readers.
    $articles = config('lab-news.articles');

    expect($articles)->not->toBeEmpty();

    foreach ($articles as $article) {
        foreach (['title_en', 'category_en', 'excerpt_en', 'image_alt_en'] as $key) {
            expect($article[$key] ?? '')->not->toBe('', "{$article['slug']} is missing {$key}");
        }

        expect($article['body_en'] ?? [])->toHaveCount(count($article['body']));

        // Stored ISO so it can be formatted per locale — a pre-formatted
        // "12 Juni 2025" could only ever read in one language.
        expect($article['date'])->toMatch('/^\d{4}-\d{2}-\d{2}$/');
    }
});

test('lab-news copy and dates resolve to the active locale', function () {
    $article = config('lab-news.articles')[0];

    app()->setLocale('id');
    expect(NewsController::localised($article, 'title'))->toBe($article['title']);
    expect(NewsController::formatDate($article['date']))->toBe('12 Juni 2025');

    app()->setLocale('en');
    expect(NewsController::localised($article, 'title'))->toBe($article['title_en']);
    expect(NewsController::formatDate($article['date']))->toBe('12 June 2025');
});
