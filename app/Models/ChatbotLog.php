<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Diagnostic trail for the chatbot. Read it to find knowledge base gaps —
 * see docs/rag-chatbot/OPERASIONAL.md.
 */
class ChatbotLog extends Model
{
    protected $fillable = [
        'question',
        'locale',
        'scope',
        'top_score',
        'answered',
    ];

    protected $casts = [
        'top_score' => 'float',
        'answered' => 'boolean',
    ];
}
