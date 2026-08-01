<?php

use App\Actions\Chatbot\AnswerQuestionAction;
use App\DTOs\Chatbot\ChatRequestData;
use App\Enums\BookingStatus;
use App\Models\ChatbotLog;
use App\Models\KnowledgeChunk;
use App\Models\ServiceBooking;
use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'gemini.api_key' => 'test-key',
        'gemini.base_url' => 'https://example.test/v1beta',
        'gemini.chat_model' => 'test-chat-model',
        'gemini.embedding_model' => 'test-embedding-model',
        'gemini.embedding_dimensions' => 4,
        'gemini.retrieval.top_k' => 5,
        'gemini.retrieval.min_score' => 0.55,
        'gemini.generation.thinking_level' => 'low',
        'gemini.history_turns' => 3,
    ]);

    $this->generateResponse = Http::response([
        'candidates' => [['content' => ['parts' => [['text' => 'Biaya cetak 3D dihitung per gram.']]]]],
    ]);

    Http::fake([
        '*:embedContent' => Http::response(['embedding' => ['values' => [1.0, 0.0, 0.0, 0.0]]]),
        // Resolved per request rather than fixed here: Http::fake() appends stubs and the
        // first match wins, so a test could not otherwise override this one.
        '*:generateContent' => fn () => $this->generateResponse,
    ]);
});

/** @param  array<int, float>  $vector */
function knowledge(array $vector = [1.0, 0.0, 0.0, 0.0], array $attributes = []): KnowledgeChunk
{
    return KnowledgeChunk::create([
        'source_key' => $attributes['source_key'] ?? 'md:id:'.uniqid(),
        'source_hash' => str_repeat('a', 64),
        'locale' => $attributes['locale'] ?? 'id',
        'audience' => $attributes['audience'] ?? 'public',
        'category' => 'layanan',
        'title' => $attributes['title'] ?? 'Prosedur Pemesanan',
        'heading' => $attributes['heading'] ?? 'Biaya cetak 3D',
        'content' => $attributes['content'] ?? 'Biaya cetak 3D dihitung Rp 5.000 per gram bahan.',
        // array_key_exists, not ??: a test that deliberately passes url => null must get
        // null, and ?? would quietly hand it the default instead.
        'url' => array_key_exists('url', $attributes) ? $attributes['url'] : '/services',
        'embedding' => pack('g*', ...$vector),
        'dimensions' => count($vector),
    ]);
}

function ask(array $overrides = []): array
{
    return app(AnswerQuestionAction::class)->execute(new ChatRequestData(
        question: $overrides['question'] ?? 'Berapa harga cetak 3D?',
        history: $overrides['history'] ?? [],
        locale: $overrides['locale'] ?? 'id',
        user: $overrides['user'] ?? null,
    ));
}

it('answers from the retrieved context and returns its sources', function () {
    knowledge();

    $result = ask();

    expect($result['answered'])->toBeTrue()
        ->and($result['answer'])->toBe('Biaya cetak 3D dihitung per gram.')
        ->and($result['sources'])->toBe([['title' => 'Prosedur Pemesanan', 'url' => '/services']]);

    Http::assertSent(fn (Request $r) => str_contains((string) $r->url(), ':generateContent')
        && str_contains($r['contents'][0]['parts'][0]['text'], 'CONTEXT')
        && str_contains($r['contents'][0]['parts'][0]['text'], 'Rp 5.000 per gram'));
});

it('returns the fallback without calling the model when nothing matches', function () {
    knowledge([0.0, 1.0, 0.0, 0.0]); // score 0.0, below the threshold

    $result = ask();

    expect($result['answered'])->toBeFalse()
        ->and($result['answer'])->toContain('admin Laboratorium')
        ->and($result['sources'])->toBe([]);

    // The whole point of the early return: a model handed an empty context still invents a
    // confident answer, and each call spends quota that is the real constraint here.
    Http::assertNotSent(fn (Request $r) => str_contains((string) $r->url(), ':generateContent'));
});

it('returns the English fallback for an English question', function () {
    $result = ask(['locale' => 'en', 'question' => 'How much does 3D printing cost?']);

    expect($result['answered'])->toBeFalse()
        ->and($result['answer'])->toContain('IDIG lab admin');
});

it('never sends order context for a guest', function () {
    knowledge();

    $user = User::factory()->create();
    ServiceBooking::factory()->create(['user_id' => $user->id, 'current_status' => BookingStatus::Printing->value]);

    ask(); // no user on the DTO

    Http::assertSent(fn (Request $r) => ! str_contains((string) $r->url(), ':generateContent')
        || ! str_contains($r['contents'][0]['parts'][0]['text'], 'ACCOUNT ORDER DATA'));
});

it('includes the signed-in user\'s own orders only', function () {
    knowledge();

    $mine = User::factory()->create();
    $theirs = User::factory()->create();

    $myBooking = ServiceBooking::factory()->create(['user_id' => $mine->id, 'current_status' => BookingStatus::Printing->value]);
    $theirBooking = ServiceBooking::factory()->create(['user_id' => $theirs->id, 'current_status' => BookingStatus::Printing->value]);

    ask(['user' => $mine]);

    Http::assertSent(function (Request $r) use ($myBooking, $theirBooking) {
        if (! str_contains((string) $r->url(), ':generateContent')) {
            return false;
        }

        $prompt = $r['contents'][0]['parts'][0]['text'];

        return str_contains($prompt, 'ACCOUNT ORDER DATA')
            && str_contains($prompt, 'INV-'.str_pad((string) $myBooking->id, 4, '0', STR_PAD_LEFT))
            && ! str_contains($prompt, 'INV-'.str_pad((string) $theirBooking->id, 4, '0', STR_PAD_LEFT));
    });
});

it('answers a signed-in user from their orders even with no matching document', function () {
    knowledge([0.0, 1.0, 0.0, 0.0]); // nothing passes the threshold

    $user = User::factory()->create();
    ServiceBooking::factory()->create(['user_id' => $user->id, 'current_status' => BookingStatus::Printing->value]);

    $result = ask(['user' => $user, 'question' => 'Pesanan saya sampai mana?']);

    expect($result['answered'])->toBeTrue();

    Http::assertSent(fn (Request $r) => ! str_contains((string) $r->url(), ':generateContent')
        || (str_contains($r['contents'][0]['parts'][0]['text'], 'ACCOUNT ORDER DATA')
            && ! str_contains($r['contents'][0]['parts'][0]['text'], 'CONTEXT')));
});

it('carries the mandatory guardrails in the system instruction', function () {
    knowledge();

    ask();

    Http::assertSent(function (Request $r) {
        if (! str_contains((string) $r->url(), ':generateContent')) {
            return false;
        }

        $system = $r['systemInstruction']['parts'][0]['text'];

        return str_contains($system, 'ONLY from the CONTEXT')
            && str_contains($system, 'Never guess a price')
            // The lab's name contains "teknologi kesehatan", so a medical question is a
            // matter of when, not if.
            && str_contains($system, 'no diagnosis')
            && str_contains($system, 'Never invent a link')
            && str_contains($system, 'Reply in Indonesian')
            && str_contains($system, 'three short paragraphs');
    });
});

it('asks for an English reply when the locale is English', function () {
    knowledge([1.0, 0.0, 0.0, 0.0], ['locale' => 'en']);

    ask(['locale' => 'en']);

    Http::assertSent(fn (Request $r) => ! str_contains((string) $r->url(), ':generateContent')
        || str_contains($r['systemInstruction']['parts'][0]['text'], 'Reply in English'));
});

it('tells the model to reply in English off Indonesian context on the fallback path', function () {
    knowledge([1.0, 0.0, 0.0, 0.0], ['locale' => 'id']);

    ask(['locale' => 'en']);

    Http::assertSent(fn (Request $r) => ! str_contains((string) $r->url(), ':generateContent')
        || str_contains($r['systemInstruction']['parts'][0]['text'], 'CONTEXT is written in Indonesian'));
});

it('replays only the configured number of turns', function () {
    config(['gemini.history_turns' => 1]);
    knowledge();

    ask(['history' => [
        ['role' => 'user', 'content' => 'Pertanyaan lama'],
        ['role' => 'assistant', 'content' => 'Jawaban lama'],
        ['role' => 'user', 'content' => 'Pertanyaan baru'],
        ['role' => 'assistant', 'content' => 'Jawaban baru'],
    ]]);

    Http::assertSent(function (Request $r) {
        if (! str_contains((string) $r->url(), ':generateContent')) {
            return false;
        }

        $texts = collect($r['contents'])->pluck('parts.0.text');

        return $texts->doesntContain('Pertanyaan lama')
            && $texts->contains('Pertanyaan baru')
            && $texts->contains('Jawaban baru');
    });
});

it('logs every question with its top score', function () {
    knowledge();

    ask();

    $log = ChatbotLog::query()->sole();

    expect($log->question)->toBe('Berapa harga cetak 3D?')
        ->and($log->locale)->toBe('id')
        ->and($log->scope)->toBe('public')
        ->and($log->answered)->toBeTrue()
        ->and($log->top_score)->toEqual(1.0);
});

it('logs an unanswered question so the gap is findable', function () {
    knowledge([0.0, 1.0, 0.0, 0.0]);

    ask();

    $log = ChatbotLog::query()->sole();

    // 0.0, not null: retrieval ran and matched nothing. That is the row worth reviewing.
    expect($log->answered)->toBeFalse()
        ->and($log->top_score)->toEqual(0.0);
});

it('records the authenticated scope', function () {
    knowledge();

    ask(['user' => User::factory()->create()]);

    expect(ChatbotLog::query()->sole()->scope)->toBe('authenticated');
});

it('still returns the answer when the log write fails', function () {
    knowledge();

    // Losing the diagnostic row is a smaller problem than losing the user's reply.
    Schema::drop('chatbot_logs');

    expect(ask()['answer'])->toBe('Biaya cetak 3D dihitung per gram.');
});

it('logs a failed generation as unanswered and rethrows', function () {
    knowledge();

    $this->generateResponse = Http::response(['error' => ['message' => 'quota exceeded']], 429);

    expect(fn () => ask())->toThrow(RuntimeException::class, 'quota exceeded');

    expect(ChatbotLog::query()->sole())
        ->answered->toBeFalse()
        ->top_score->toEqual(1.0);
});

it('drops sources with no url', function () {
    knowledge([1.0, 0.0, 0.0, 0.0], ['url' => null, 'heading' => 'Tanpa tautan']);
    knowledge([0.9, 0.435889, 0.0, 0.0], ['url' => '/services', 'title' => 'Layanan']);

    expect(ask()['sources'])->toBe([['title' => 'Layanan', 'url' => '/services']]);
});
