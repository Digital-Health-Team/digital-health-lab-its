<?php

namespace App\Actions\Chatbot;

use App\Models\KnowledgeChunk;
use App\Services\GeminiClient;
use Illuminate\Support\Facades\Cache;

/**
 * Finds the passages that can answer a question.
 *
 * Similarity runs in PHP over a cached, unpacked copy of the index. The knowledge base is
 * a few hundred chunks per locale, so a full scan is a handful of milliseconds — and it
 * behaves identically on MySQL and the SQLite in-memory database the tests use, which a
 * pgvector implementation could not. Past roughly 5,000 chunks, swap the internals for
 * pgvector; the public contract here does not have to change.
 */
class RetrieveKnowledgeAction
{
    public function __construct(private readonly GeminiClient $gemini) {}

    /**
     * @param  bool  $authenticated  Whether the asker is signed in. Widens the audience
     *                               filter to include `authenticated` chunks; it never
     *                               grants access to anyone's account data, which reaches
     *                               the prompt through BuildUserOrderContextAction instead.
     * @return array{chunks: array<int, array{title: string, heading: ?string, content: string, url: ?string, score: float}>, top_score: float, locale: string, fallback: bool}
     */
    public function execute(string $question, string $locale, bool $authenticated = false): array
    {
        $question = trim($question);

        if ($question === '') {
            return $this->result([], $locale, false);
        }

        $queryVector = $this->gemini->embed($question, GeminiClient::TASK_QUERY);

        $matches = $this->search($queryVector, $locale, $authenticated);

        // HasEnglishOverlay lets half-translated rows fall back to Indonesian, so an
        // English question with no English match is better served by Indonesian context
        // than by giving up. AnswerQuestionAction reads the flag and still instructs the
        // model to reply in English.
        if ($matches === [] && $locale === 'en') {
            $fallbackMatches = $this->search($queryVector, 'id', $authenticated);

            if ($fallbackMatches !== []) {
                return $this->result($fallbackMatches, 'id', true);
            }
        }

        return $this->result($matches, $locale, false);
    }

    /**
     * Drop every cached chunk list. Called by BuildKnowledgeIndexAction after each run —
     * without it a reindex stays invisible for up to `retrieval.cache_ttl` seconds.
     */
    public static function flushCache(): void
    {
        foreach (KnowledgeChunk::LOCALES as $locale) {
            foreach ([true, false] as $authenticated) {
                Cache::forget(self::cacheKey($locale, $authenticated));
            }
        }
    }

    /**
     * @param  array<int, float>  $queryVector
     * @return array<int, array{title: string, heading: ?string, content: string, url: ?string, score: float}>
     */
    private function search(array $queryVector, string $locale, bool $authenticated): array
    {
        $minScore = (float) config('gemini.retrieval.min_score');
        $topK = (int) config('gemini.retrieval.top_k');
        $scored = [];

        foreach ($this->chunks($locale, $authenticated) as $chunk) {
            if (count($chunk['vector']) !== count($queryVector)) {
                // Left over from a previous embedding_dimensions value. Comparing across
                // widths would produce a confident, meaningless score; `--fresh` is the fix.
                continue;
            }

            $score = $this->dot($queryVector, $chunk['vector']);

            if ($score < $minScore) {
                continue;
            }

            $scored[] = [
                'title' => $chunk['title'],
                'heading' => $chunk['heading'],
                'content' => $chunk['content'],
                'url' => $chunk['url'],
                'score' => $score,
            ];
        }

        usort($scored, fn (array $a, array $b): int => $b['score'] <=> $a['score']);

        return array_slice($scored, 0, max(1, $topK));
    }

    /**
     * Both vectors are already L2-normalised by GeminiClient, so the dot product IS the
     * cosine similarity. Dividing by magnitudes again here would be wasted work on every
     * chunk of every question.
     *
     * @param  array<int, float>  $a
     * @param  array<int, float>  $b
     */
    private function dot(array $a, array $b): float
    {
        $sum = 0.0;

        foreach ($a as $i => $value) {
            $sum += $value * $b[$i];
        }

        return $sum;
    }

    /**
     * Chunks for one locale and audience, unpacked and cached.
     *
     * @return array<int, array{title: string, heading: ?string, content: string, url: ?string, vector: array<int, float>}>
     */
    private function chunks(string $locale, bool $authenticated): array
    {
        return Cache::remember(
            self::cacheKey($locale, $authenticated),
            (int) config('gemini.retrieval.cache_ttl'),
            fn (): array => KnowledgeChunk::query()
                ->select(['title', 'heading', 'content', 'url', 'embedding'])
                ->where('locale', $locale)
                // Public mode sees public chunks only. A signed-in asker also sees chunks
                // an author marked `authenticated` — copy that is only meaningful once you
                // have an account, never anyone's data.
                ->whereIn('audience', $authenticated ? ['public', 'authenticated'] : ['public'])
                ->get()
                ->map(fn (KnowledgeChunk $chunk): array => [
                    'title' => (string) $chunk->title,
                    'heading' => $chunk->heading,
                    'content' => (string) $chunk->content,
                    'url' => $chunk->url,
                    'vector' => $chunk->vector(),
                ])
                ->all(),
        );
    }

    private static function cacheKey(string $locale, bool $authenticated): string
    {
        return sprintf('%s:%s:%s', KnowledgeChunk::CACHE_KEY, $locale, $authenticated ? 'auth' : 'public');
    }

    /**
     * @param  array<int, array{title: string, heading: ?string, content: string, url: ?string, score: float}>  $chunks
     * @return array{chunks: array<int, array{title: string, heading: ?string, content: string, url: ?string, score: float}>, top_score: float, locale: string, fallback: bool}
     */
    private function result(array $chunks, string $locale, bool $fallback): array
    {
        return [
            'chunks' => $chunks,
            // 0.0 rather than null: retrieval ran and matched nothing, which is the signal
            // worth logging. See docs/rag-chatbot/OPERASIONAL.md.
            'top_score' => $chunks === [] ? 0.0 : $chunks[0]['score'],
            'locale' => $locale,
            'fallback' => $fallback,
        ];
    }
}
