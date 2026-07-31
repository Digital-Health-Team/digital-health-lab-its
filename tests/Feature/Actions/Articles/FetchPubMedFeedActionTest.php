<?php

use App\Actions\Articles\FetchPubMedFeedAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

function pubmedRssFixture(int $count = 2): string
{
    $items = '';
    for ($i = 1; $i <= $count; $i++) {
        $items .= <<<XML
        <item>
            <title>Article Title {$i}</title>
            <link>https://pubmed.ncbi.nlm.nih.gov/3800000{$i}/</link>
            <description>Abstract text for article {$i}.</description>
            <author>Author {$i}</author>
            <category>Test Journal</category>
            <pubDate>Mon, 01 Jan 2025 00:00:00 +0000</pubDate>
        </item>
        XML;
    }

    return <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <rss version="2.0">
        <channel>
            <title>PubMed Search</title>
            {$items}
        </channel>
    </rss>
    XML;
}

beforeEach(function () {
    Cache::flush();
});

it('returns parsed items from a successful feed', function () {
    Http::fake([
        'pubmed.ncbi.nlm.nih.gov/*' => Http::response(pubmedRssFixture(5), 200),
    ]);

    $result = (new FetchPubMedFeedAction)->execute();

    expect($result)->toHaveCount(5);
    expect($result[0])->toHaveKeys(['id', 'pmid', 'title', 'authors', 'journal', 'publishedAt', 'abstract', 'tags', 'href']);
    expect($result[0]['title'])->toBe('Article Title 1');
    expect($result[0]['pmid'])->toBe('38000001');
    expect($result[0]['href'])->toBe('https://pubmed.ncbi.nlm.nih.gov/38000001/');
});

it('caches the result so HTTP is only called once', function () {
    Http::fake([
        'pubmed.ncbi.nlm.nih.gov/*' => Http::response(pubmedRssFixture(2), 200),
    ]);

    $action = new FetchPubMedFeedAction;
    $action->execute();
    $action->execute();

    Http::assertSentCount(1);
});

it('returns empty array when the feed responds with a non-200 status', function () {
    Http::fake([
        'pubmed.ncbi.nlm.nih.gov/*' => Http::response('', 500),
    ]);

    $result = (new FetchPubMedFeedAction)->execute();

    expect($result)->toBeArray()->toBeEmpty();
});

it('returns empty array when the feed returns invalid xml', function () {
    Http::fake([
        'pubmed.ncbi.nlm.nih.gov/*' => Http::response('not xml at all', 200),
    ]);

    $result = (new FetchPubMedFeedAction)->execute();

    expect($result)->toBeArray()->toBeEmpty();
});
