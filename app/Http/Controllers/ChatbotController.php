<?php

namespace App\Http\Controllers;

use App\Actions\Chatbot\AnswerQuestionAction;
use App\DTOs\Chatbot\ChatRequestData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChatbotController extends Controller
{
    public function ask(Request $request, AnswerQuestionAction $answer): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:500'],
            'history' => ['sometimes', 'array', 'max:12'],
            'history.*.role' => ['required', 'string', 'in:user,assistant'],
            // Bounded, but generously: max_output_tokens is 900, and Indonesian runs about
            // five characters per token, so our own answers reach ~4,500 characters. A
            // tighter cap would make the widget's next request fail validation on the
            // transcript we produced ourselves. What actually reaches Gemini is capped by
            // gemini.history_turns, which is the limit that protects the token quota.
            'history.*.content' => ['required', 'string', 'max:5000'],
        ]);

        $data = new ChatRequestData(
            question: $validated['message'],
            history: $validated['history'] ?? [],
            locale: app()->getLocale(),
            // From the session, never from the body. A user_id accepted from the request is
            // all it takes to read someone else's orders.
            user: $request->user(),
        );

        try {
            return response()->json($answer->execute($data));
        } catch (Throwable $e) {
            Log::error('[Chatbot] Answering failed.', ['exception' => $e->getMessage()]);

            // 503, not 500: the widget tells "try again shortly" apart from the 429 the
            // throttle returns, and neither should surface as a generic failure.
            return response()->json([
                'answer' => $this->unavailableMessage(),
                'sources' => [],
                'answered' => false,
            ], 503);
        }
    }

    private function unavailableMessage(): string
    {
        return app()->getLocale() === 'en'
            ? 'The assistant is unavailable right now. Please try again shortly, or contact the IDIG lab admin.'
            : 'Asisten sedang tidak tersedia. Silakan coba lagi sebentar lagi, atau hubungi admin Laboratorium Teknologi Kesehatan IDIG.';
    }
}
