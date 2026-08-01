<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * HTTP client for the Google Generative Language API.
 *
 * Two calls, nothing else: embed text for the vector index, and generate an answer from
 * a context block. Model ids come from config/gemini.php, which reads them from .env —
 * no model name is written into this file, because Google's catalogue moves faster than
 * the code does.
 */
class GeminiClient
{
    public const TASK_DOCUMENT = 'RETRIEVAL_DOCUMENT';

    public const TASK_QUERY = 'RETRIEVAL_QUERY';

    /**
     * Embed one passage and return an L2-normalised vector.
     *
     * $taskType is the caller's decision, not a default: RETRIEVAL_DOCUMENT while
     * indexing, RETRIEVAL_QUERY while answering. The model embeds asymmetrically for the
     * two, and using the wrong one measurably degrades search accuracy without failing.
     *
     * @return array<int, float>
     */
    public function embed(string $text, string $taskType): array
    {
        $dimensions = (int) config('gemini.embedding_dimensions');
        $model = $this->model('embedding_model');

        $response = $this->post("{$model}:embedContent", [
            'model' => "models/{$model}",
            'content' => ['parts' => [['text' => $text]]],
            'taskType' => $taskType,
            'outputDimensionality' => $dimensions,
        ]);

        /** @var array<int, float>|null $values */
        $values = $response->json('embedding.values');

        if (! is_array($values) || $values === []) {
            $this->fail('Gemini returned no embedding values.', $response);
        }

        return $this->normalize(array_map('floatval', $values));
    }

    /**
     * Generate an answer. Returns the model's text, never null.
     *
     * @param  array<int, array{role: string, content: string}>  $history
     */
    public function generate(string $systemInstruction, array $history, string $prompt): string
    {
        $model = $this->model('chat_model');

        $contents = [];

        foreach ($history as $turn) {
            $content = trim((string) ($turn['content'] ?? ''));

            if ($content === '') {
                continue;
            }

            $contents[] = [
                // Our side says "assistant"; Gemini's only non-user role is "model".
                'role' => ($turn['role'] ?? 'user') === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $content]],
            ];
        }

        $contents[] = ['role' => 'user', 'parts' => [['text' => $prompt]]];

        $response = $this->post("{$model}:generateContent", [
            'systemInstruction' => ['parts' => [['text' => $systemInstruction]]],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => (float) config('gemini.generation.temperature'),
                'maxOutputTokens' => (int) config('gemini.generation.max_output_tokens'),
                // thinkingLevel only. Adding thinkingBudget alongside it is a 400, and
                // thinkingBudget belongs to the 2.5 series regardless.
                'thinkingConfig' => [
                    'thinkingLevel' => (string) config('gemini.generation.thinking_level'),
                ],
            ],
        ]);

        $text = trim(collect($response->json('candidates.0.content.parts') ?? [])
            ->pluck('text')
            ->filter()
            ->implode(''));

        if ($text === '') {
            // finishReason is the whole diagnosis here: an empty answer usually means a
            // safety block (SAFETY / PROHIBITED_CONTENT) or an exhausted token budget
            // (MAX_TOKENS), and the two need opposite fixes.
            $this->fail(sprintf(
                'Gemini returned an empty answer. finishReason=%s',
                $response->json('candidates.0.finishReason') ?? 'null',
            ), $response);
        }

        return $text;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function post(string $path, array $payload): Response
    {
        $apiKey = (string) config('gemini.api_key');

        if ($apiKey === '') {
            throw new RuntimeException('GEMINI_API_KEY is not set.');
        }

        $response = Http::withHeaders(['x-goog-api-key' => $apiKey])
            ->acceptJson()
            ->timeout(30)
            ->post(rtrim((string) config('gemini.base_url'), '/')."/models/{$path}", $payload);

        if ($response->failed()) {
            $this->fail("Gemini request to {$path} failed with HTTP {$response->status()}.", $response);
        }

        return $response;
    }

    /**
     * Log the response body verbatim, then throw.
     *
     * The body is never trimmed or filtered: Google's error messages name the offending
     * parameter, and a status code on its own leads straight to guessing.
     */
    private function fail(string $message, Response $response): never
    {
        Log::error("[GeminiClient] {$message}", ['body' => $response->body()]);

        throw new RuntimeException("{$message} Body: {$response->body()}");
    }

    private function model(string $key): string
    {
        $model = trim((string) config("gemini.{$key}"));

        if ($model === '') {
            throw new RuntimeException(
                "config/gemini.php [{$key}] is empty. Set it in .env — see docs/rag-chatbot/SETUP-GEMINI.md."
            );
        }

        return $model;
    }

    /**
     * Scale a vector to unit length.
     *
     * gemini-embedding-001 only auto-normalises at its full 3072 dimensions. We truncate
     * to 768, so this has to happen here. Skip it and a dot product stops being cosine
     * similarity: results come back ranked wrong, with no error anywhere — which is why
     * this has its own test.
     *
     * @param  array<int, float>  $vector
     * @return array<int, float>
     */
    private function normalize(array $vector): array
    {
        $magnitude = sqrt(array_sum(array_map(fn (float $v): float => $v * $v, $vector)));

        if ($magnitude <= 0.0) {
            return $vector;
        }

        return array_map(fn (float $v): float => $v / $magnitude, $vector);
    }
}
