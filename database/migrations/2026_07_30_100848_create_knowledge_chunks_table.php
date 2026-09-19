<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Vector index for the RAG chatbot. Public knowledge only.
 *
 * Order data never lands here — see docs/rag-chatbot/ARSITEKTUR.md. A single shared
 * index holding every user's bookings would turn one wrong filter into a cross-user
 * leak; BuildUserOrderContextAction reads bookings through Eloquent instead, filtered
 * by the session user_id, which makes that bug class impossible rather than unlikely.
 *
 * `embedding` is a BLOB of pack('g*') float32s, already L2-normalised by GeminiClient.
 * Similarity is a dot product computed in PHP: the knowledge base is a few hundred
 * chunks per locale, and a BLOB behaves identically on MySQL and the SQLite in-memory
 * database the test suite uses — pgvector would not be testable there at all.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_chunks', function (Blueprint $table) {
            $table->id();
            // 'md:id:faq-umum' | 'db:service:12:en' — one document, one locale.
            $table->string('source_key')->index();
            // sha256 of the whole source document, so an unchanged document can be
            // skipped without spending an embedding call on it.
            $table->string('source_hash', 64);
            $table->string('locale', 2)->index();
            $table->string('audience', 16)->index();
            $table->string('category')->index();
            $table->string('title');
            $table->string('heading')->nullable();
            $table->text('content');
            $table->string('url')->nullable();
            $table->binary('embedding');
            $table->unsignedSmallInteger('dimensions');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_chunks');
    }
};
