<?php

namespace App\Actions\Articles;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FetchPubMedFeedAction
{
    private const CACHE_KEY = 'pubmed_feed';

    private const CACHE_TTL = 3600;

    private const FEED_URL = 'https://pubmed.ncbi.nlm.nih.gov/rss/search/';

    private const SEARCH_TERM = '3d printing medical device prosthetics biomedical engineering';

    private const COUNT = 5;

    public function execute(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, fn () => $this->fetch());
    }

    private function fetch(): array
    {
        try {
            $response = Http::timeout(5)->get(self::FEED_URL, [
                'term' => self::SEARCH_TERM,
                'count' => self::COUNT,
                'format' => 'rss',
            ]);

            if (! $response->successful()) {
                return [];
            }

            return $this->parse($response->body());
        } catch (\Exception) {
            return [];
        }
    }

    private function parse(string $xml): array
    {
        libxml_use_internal_errors(true);
        $feed = simplexml_load_string($xml);

        if ($feed === false) {
            return [];
        }

        $items = [];
        $index = 0;

        foreach ($feed->channel->item ?? [] as $item) {
            $link = (string) $item->link;
            $pmid = rtrim(parse_url($link, PHP_URL_PATH) ?? '', '/');
            $pmid = ltrim(basename($pmid), '/');

            $publishedAt = null;
            $rawDate = (string) $item->pubDate;
            if ($rawDate) {
                try {
                    $publishedAt = Carbon::parse($rawDate)->toDateString();
                } catch (\Exception) {
                    $publishedAt = null;
                }
            }

            $items[] = [
                'id' => (string) $index,
                'pmid' => $pmid,
                'title' => (string) $item->title,
                'authors' => array_filter([(string) $item->author]),
                'journal' => (string) $item->category,
                'publishedAt' => $publishedAt,
                'abstract' => strip_tags((string) $item->description),
                'tags' => [],
                'href' => $link,
            ];

            $index++;
        }

        return $items;
    }
}
