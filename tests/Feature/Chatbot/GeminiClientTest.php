<?php

use App\Services\GeminiClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

/**
 * Lives in Feature/ rather than Unit/ because tests/Pest.php binds Tests\TestCase only to
 * Feature — a test under Unit/ has no Laravel application and therefore no Http::fake().
 */
beforeEach(function () {
    config([
        'gemini.api_key' => 'test-key',
        'gemini.base_url' => 'https://example.test/v1beta',
        'gemini.chat_model' => 'test-chat-model',
        'gemini.embedding_model' => 'test-embedding-model',
        'gemini.embedding_dimensions' => 4,
        'gemini.generation.temperature' => 0.2,
        'gemini.generation.max_output_tokens' => 900,
        'gemini.generation.thinking_level' => 'low',
    ]);
});

function fakeEmbedding(array $values): void
{
    Http::fake([
        '*:embedContent' => Http::response(['embedding' => ['values' => $values]]),
    ]);
}

it('normalises the embedding to unit length', function () {
    // Deliberately not unit length: |(3,4,0,0)| = 5. gemini-embedding-001 only
    // auto-normalises at its full 3072 dimensions, and we truncate, so if GeminiClient
    // stops normalising, dot product silently stops being cosine similarity.
    fakeEmbedding([3.0, 4.0, 0.0, 0.0]);

    $vector = app(GeminiClient::class)->embed('cetak 3d', GeminiClient::TASK_DOCUMENT);

    $magnitude = sqrt(array_sum(array_map(fn (float $v): float => $v * $v, $vector)));

    expect($magnitude)->toBeGreaterThan(1.0 - 1e-6)
        ->and($magnitude)->toBeLessThan(1.0 + 1e-6)
        ->and($vector)->toEqualCanonicalizing([0.6, 0.8, 0.0, 0.0]);
});

it('keeps an already normalised embedding at unit length', function () {
    fakeEmbedding([0.5, 0.5, 0.5, 0.5]);

    $vector = app(GeminiClient::class)->embed('cetak 3d', GeminiClient::TASK_DOCUMENT);

    $magnitude = sqrt(array_sum(array_map(fn (float $v): float => $v * $v, $vector)));

    expect(abs($magnitude - 1.0))->toBeLessThan(1e-6);
});

it('sends the task type chosen by the caller and the configured dimensions', function () {
    fakeEmbedding([1.0, 0.0, 0.0, 0.0]);

    app(GeminiClient::class)->embed('berapa harga cetak 3d', GeminiClient::TASK_QUERY);

    Http::assertSent(function (Request $request) {
        expect($request->url())->toBe('https://example.test/v1beta/models/test-embedding-model:embedContent')
            ->and($request->header('x-goog-api-key'))->toBe(['test-key']);

        return $request['taskType'] === 'RETRIEVAL_QUERY'
            && $request['outputDimensionality'] === 4
            && $request['content']['parts'][0]['text'] === 'berapa harga cetak 3d';
    });
});

it('throws with the full response body when embedding fails', function () {
    Http::fake([
        '*:embedContent' => Http::response(['error' => ['message' => 'models/x is not found']], 404),
    ]);

    // The body is re-emitted verbatim, JSON escaping included — Google's message names the
    // offending parameter, and trimming it is how you end up guessing from a status code.
    expect(fn () => app(GeminiClient::class)->embed('x', GeminiClient::TASK_DOCUMENT))
        ->toThrow(RuntimeException::class, '{"error":{"message":"models\/x is not found"}}');
});

it('sends thinkingLevel and never thinkingBudget', function () {
    Http::fake([
        '*:generateContent' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => 'Jawaban.']]]]],
        ]),
    ]);

    app(GeminiClient::class)->generate('Kamu asisten lab.', [], 'Berapa harga cetak 3D?');

    Http::assertSent(function (Request $request) {
        $config = $request['generationConfig'];

        // Both parameters in one request is a 400, and thinkingBudget belongs to the 2.5
        // series regardless — it must not appear anywhere.
        return $config['thinkingConfig']['thinkingLevel'] === 'low'
            && ! array_key_exists('thinkingBudget', $config)
            && ! array_key_exists('thinkingBudget', $config['thinkingConfig'])
            && $config['temperature'] === 0.2
            && $config['maxOutputTokens'] === 900;
    });
});

it('maps the assistant role to model and appends the prompt last', function () {
    Http::fake([
        '*:generateContent' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => 'Jawaban.']]]]],
        ]),
    ]);

    app(GeminiClient::class)->generate('Sistem.', [
        ['role' => 'user', 'content' => 'Halo'],
        ['role' => 'assistant', 'content' => 'Ada yang bisa dibantu?'],
        ['role' => 'assistant', 'content' => '   '],
    ], 'Berapa harga cetak 3D?');

    Http::assertSent(function (Request $request) {
        expect($request['systemInstruction']['parts'][0]['text'])->toBe('Sistem.');

        return collect($request['contents'])->pluck('role')->all() === ['user', 'model', 'user']
            && $request['contents'][2]['parts'][0]['text'] === 'Berapa harga cetak 3D?';
    });
});

it('reports the finish reason when the model returns nothing', function () {
    Http::fake([
        '*:generateContent' => Http::response(['candidates' => [['finishReason' => 'SAFETY']]]),
    ]);

    expect(fn () => app(GeminiClient::class)->generate('Sistem.', [], 'Halo'))
        ->toThrow(RuntimeException::class, 'finishReason=SAFETY');
});

it('refuses to call the API when the model id is not configured', function () {
    config(['gemini.embedding_model' => '']);
    Http::fake();

    expect(fn () => app(GeminiClient::class)->embed('x', GeminiClient::TASK_DOCUMENT))
        ->toThrow(RuntimeException::class, 'embedding_model');

    Http::assertNothingSent();
});
