<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Credentials and endpoint
    |--------------------------------------------------------------------------
    */

    'api_key' => env('GEMINI_API_KEY'),

    'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Deliberately without defaults. Google retires Gemini models quickly, and a model
    | that still answers for an older account can return 404 for a new one — so a
    | hardcoded fallback here would mean the app silently calls a model nobody verified.
    | An empty value is a misconfiguration and GeminiClient says so.
    |
    | Verify what your own key can reach before filling these in; the procedure is in
    | docs/rag-chatbot/SETUP-GEMINI.md. Avoid aliases like `-latest`: the model behind an
    | alias can change without warning, and a chatbot that obeyed "never guess a price"
    | can start hallucinating after Google repoints it.
    |
    */

    'chat_model' => env('GEMINI_CHAT_MODEL'),

    'embedding_model' => env('GEMINI_EMBEDDING_MODEL'),

    /*
    | Changing this after the first ingest REQUIRES `php artisan chatbot:index --fresh`.
    | Vectors of different lengths cannot be compared, so a mixed index does not error —
    | it just returns nonsense rankings. Truncated dimensions are also why GeminiClient
    | normalises by hand: the model only auto-normalises at its full width.
    */
    'embedding_dimensions' => (int) env('GEMINI_EMBEDDING_DIMENSIONS', 768),

    /*
    |--------------------------------------------------------------------------
    | Knowledge base
    |--------------------------------------------------------------------------
    |
    | Root of the hand-written markdown, holding one subdirectory per locale
    | (knowledge/id, knowledge/en). Overridable so the ingest tests can point at a
    | temporary directory instead of writing into the repository.
    |
    */

    'knowledge_path' => base_path('knowledge'),

    /*
    |--------------------------------------------------------------------------
    | Retrieval
    |--------------------------------------------------------------------------
    */

    'retrieval' => [
        'top_k' => (int) env('RAG_TOP_K', 5),
        'min_score' => (float) env('RAG_MIN_SCORE', 0.55),
        'cache_ttl' => (int) env('RAG_CACHE_TTL', 300),
    ],

    /*
    |--------------------------------------------------------------------------
    | Generation
    |--------------------------------------------------------------------------
    */

    'generation' => [
        'temperature' => 0.2,

        'max_output_tokens' => 900,

        /*
        | Sent as generationConfig.thinkingConfig.thinkingLevel — a STRING, used by the
        | Gemini 3 series. The 2.5 series used thinkingBudget (a number) instead; sending
        | both in one request is a 400, so thinkingBudget appears nowhere in this codebase.
        |
        | Gemini 3 Flash and Flash-Lite cannot switch thinking off at all, so `low` is the
        | floor rather than zero. Setting it explicitly still matters: omit the parameter
        | and the model defaults to `medium`, spending thinking tokens on trivial FAQ
        | answers — and the daily quota is this project's real constraint.
        */
        'thinking_level' => env('GEMINI_THINKING_LEVEL', 'low'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Endpoint rate limits
    |--------------------------------------------------------------------------
    |
    | Requests per minute for POST /chatbot/ask, keyed by user id when signed in and by IP
    | otherwise. These exist to protect the Gemini daily quota, not the web server.
    |
    | Guests get the same budget as signed-in users on purpose: the chatbot is a public
    | tool, and an IP key is shared by everyone behind one campus NAT, so a tighter guest
    | limit throttles a room full of visitors rather than an abuser.
    |
    | The trade-off is real, so read your project's RPD in AI Studio: a single bot can spend
    | 20/min against the daily quota, and if that quota is only a few hundred requests these
    | numbers are too generous. Lower them via .env — no code change needed.
    |
    */

    'throttle' => [
        'user' => (int) env('CHATBOT_THROTTLE_USER', 20),
        'guest' => (int) env('CHATBOT_THROTTLE_GUEST', 20),
    ],

    /*
    |--------------------------------------------------------------------------
    | Conversation and ingest pacing
    |--------------------------------------------------------------------------
    */

    /** Prior turns replayed to the model. Each turn costs input tokens on every question. */
    'history_turns' => (int) env('GEMINI_HISTORY_TURNS', 3),

    /*
    | Pause between embedding calls during ingest. Free-tier RPM differs per project and
    | region; raise this if the first full index run hits 429 part-way through.
    */
    'ingest_delay_ms' => (int) env('RAG_EMBED_DELAY_MS', 250),

];
