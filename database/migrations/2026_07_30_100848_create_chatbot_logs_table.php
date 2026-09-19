<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per question asked. `top_score` is the point of this table: rows with a low
 * best-match score are the knowledge base gaps, and rewriting those questions into
 * knowledge/id/faq-umum.md is what makes the chatbot more accurate over time without
 * training anything. See docs/rag-chatbot/FORMAT-KNOWLEDGE.md ("Cara merawat").
 *
 * No user_id column: the log exists to find missing documents, not to build a profile
 * of who asked what.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_logs', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->string('locale', 2);
            // 'public' | 'authenticated' — which retrieval mode served the question.
            $table->string('scope', 16);
            // Null when retrieval never ran; 0.0 when it ran and matched nothing.
            $table->float('top_score')->nullable();
            $table->boolean('answered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_logs');
    }
};
