<?php

namespace App\DTOs\Chatbot;

use App\Models\User;

class ChatRequestData
{
    /**
     * @param  array<int, array{role: string, content: string}>  $history  Oldest first.
     *                                                                     Roles are 'user' or 'assistant'; GeminiClient maps the latter to 'model'.
     * @param  User|null  $user  Resolved from the session by the controller. Never built
     *                           from a request field — a user_id accepted from the body is
     *                           all it takes to read someone else's orders.
     */
    public function __construct(
        public string $question,
        public array $history = [],
        public string $locale = 'id',
        public ?User $user = null,
    ) {}

    public function isAuthenticated(): bool
    {
        return $this->user !== null;
    }
}
