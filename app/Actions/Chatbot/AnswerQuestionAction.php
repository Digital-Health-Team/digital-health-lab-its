<?php

namespace App\Actions\Chatbot;

use App\DTOs\Chatbot\ChatRequestData;
use App\Models\ChatbotLog;
use App\Services\GeminiClient;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Orchestrates one question: retrieve, assemble context, generate, log.
 *
 * The order of operations in docs/rag-chatbot/ARSITEKTUR.md matters most at step two —
 * with no matching chunk and no account context, this returns a fixed answer without
 * calling Gemini at all. That saves quota and closes the most common hallucination path:
 * a model handed an empty context still produces a confident, invented answer.
 */
class AnswerQuestionAction
{
    public function __construct(
        private readonly RetrieveKnowledgeAction $retrieve,
        private readonly BuildUserOrderContextAction $orderContext,
        private readonly GeminiClient $gemini,
    ) {}

    /**
     * @return array{answer: string, sources: array<int, array{title: string, url: string}>, answered: bool}
     */
    public function execute(ChatRequestData $data): array
    {
        $retrieved = $this->retrieve->execute(
            $data->question,
            $data->locale,
            $data->isAuthenticated(),
        );

        $orders = $data->user === null ? [] : $this->orderContext->execute($data->user);

        if ($retrieved['chunks'] === [] && $orders === []) {
            $this->log($data, $retrieved['top_score'], answered: false);

            return [
                'answer' => $this->fallbackAnswer($data->locale),
                'sources' => [],
                'answered' => false,
            ];
        }

        try {
            $answer = $this->gemini->generate(
                $this->systemInstruction($data->locale, $retrieved['fallback']),
                $this->history($data->history),
                $this->prompt($data->question, $retrieved['chunks'], $orders),
            );
        } catch (Throwable $e) {
            // Logged as unanswered so a run of API failures is visible in chatbot_logs
            // alongside the questions that simply had no matching document.
            $this->log($data, $retrieved['top_score'], answered: false);

            throw $e;
        }

        $this->log($data, $retrieved['top_score'], answered: true);

        return [
            'answer' => $answer,
            'sources' => $this->sources($retrieved['chunks']),
            'answered' => true,
        ];
    }

    private function systemInstruction(string $locale, bool $usedFallbackLocale): string
    {
        $language = $locale === 'en' ? 'English' : 'Indonesian';

        // Written in English on purpose: the model follows an English system prompt fine,
        // and the reply language is set by the instruction below, not by this text.
        $instruction = <<<TXT
            You are the assistant for Laboratorium Teknologi Kesehatan IDIG at Institut Teknologi
            Sepuluh Nopember (ITS), Surabaya. The lab runs 3D printing and 3D scanning services,
            publishes research, and archives student innovations.

            Follow every rule below without exception.

            1. Answer ONLY from the CONTEXT block, and from the ACCOUNT ORDER DATA block when one
               is present. Treat nothing outside those blocks as fact.
            2. If the answer is not in those blocks, say so plainly and point the person to the lab
               admin. Do not fill the gap with a plausible guess.
            3. Never guess a price, a date, a duration, a quantity or a capacity. A number that is
               not written in the context does not exist as far as you are concerned.
            4. Give no medical advice, no diagnosis, no treatment recommendation, and no
               interpretation of any medical result, image or measurement. This lab builds health
               technology; it does not practise medicine. Refer medical questions to a qualified
               health professional.
            5. Never invent a link. Use only URLs that appear in the context.
            6. Reply in {$language}. At most three short paragraphs. The tone is measured and
               expert, like a senior researcher: no chattiness, no sales language, no emoji.
            TXT;

        if ($usedFallbackLocale) {
            // HasEnglishOverlay lets a half-translated row fall back to Indonesian, and so
            // does retrieval — answering in English off Indonesian context beats refusing.
            $instruction .= "\n\nThe CONTEXT is written in Indonesian. Translate what you need and still reply in English.";
        }

        return $instruction;
    }

    /**
     * @param  array<int, array{title: string, heading: ?string, content: string, url: ?string, score: float}>  $chunks
     * @param  array<int, string>  $orders
     */
    private function prompt(string $question, array $chunks, array $orders): string
    {
        $blocks = [];

        if ($chunks !== []) {
            $passages = [];

            foreach ($chunks as $index => $chunk) {
                $header = $chunk['heading'] === null
                    ? $chunk['title']
                    : "{$chunk['title']} — {$chunk['heading']}";

                $passages[] = sprintf(
                    "[%d] %s\n%s%s",
                    $index + 1,
                    $header,
                    $chunk['content'],
                    $chunk['url'] === null ? '' : "\nURL: {$chunk['url']}",
                );
            }

            $blocks[] = "CONTEXT\n".implode("\n\n", $passages);
        }

        if ($orders !== []) {
            $blocks[] = "ACCOUNT ORDER DATA (this person's own orders, newest first)\n"
                ."columns: invoice | service | stage | progress | payment termins | created\n"
                .implode("\n", $orders);
        }

        $blocks[] = "QUESTION\n{$question}";

        return implode("\n\n", $blocks);
    }

    /**
     * Trim the replayed conversation. Each turn costs input tokens on every question, and
     * a chatbot answering FAQs rarely needs more than the last few exchanges.
     *
     * @param  array<int, array{role: string, content: string}>  $history
     * @return array<int, array{role: string, content: string}>
     */
    private function history(array $history): array
    {
        $messages = (int) config('gemini.history_turns') * 2;

        return $messages <= 0 ? [] : array_slice($history, -$messages);
    }

    /**
     * @param  array<int, array{title: string, heading: ?string, content: string, url: ?string, score: float}>  $chunks
     * @return array<int, array{title: string, url: string}>
     */
    private function sources(array $chunks): array
    {
        return collect($chunks)
            ->filter(fn (array $chunk): bool => ! in_array($chunk['url'], [null, ''], true))
            ->map(fn (array $chunk): array => ['title' => $chunk['title'], 'url' => (string) $chunk['url']])
            ->unique('url')
            ->values()
            ->all();
    }

    /**
     * Shown verbatim, so unlike the system instruction it has to be in the asker's
     * language. Kept here rather than in lang/*.json: a missing key would degrade this one
     * string to English, and it is the only thing a stuck visitor sees.
     */
    private function fallbackAnswer(string $locale): string
    {
        return $locale === 'en'
            ? 'I have no information on that yet. Please contact the IDIG lab admin through the '
                .'contact details on the website, and they can answer directly.'
            : 'Informasi itu belum tersedia untuk saya. Silakan hubungi admin Laboratorium '
                .'Teknologi Kesehatan IDIG lewat kontak yang tercantum di situs ini agar bisa dijawab langsung.';
    }

    /**
     * A logging failure must never cost the user their answer — the log exists to find
     * knowledge base gaps, and a broken write is a smaller problem than a lost reply.
     */
    private function log(ChatRequestData $data, float $topScore, bool $answered): void
    {
        try {
            ChatbotLog::create([
                'question' => $data->question,
                'locale' => $data->locale,
                'scope' => $data->isAuthenticated() ? 'authenticated' : 'public',
                'top_score' => $topScore,
                'answered' => $answered,
            ]);
        } catch (Throwable $e) {
            Log::warning('[Chatbot] Could not write chatbot_logs.', ['exception' => $e->getMessage()]);
        }
    }
}
