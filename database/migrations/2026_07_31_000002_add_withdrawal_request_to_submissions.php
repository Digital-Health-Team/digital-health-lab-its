<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Approved work is live on the public site, so a user cannot delete it outright.
 * The trash button on an approved card records a removal request here instead, and an
 * admin confirms it from the moderation queue.
 */
return new class extends Migration
{
    private array $tables = ['publications', 'open_source_projects'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->timestamp('withdrawal_requested_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('withdrawal_requested_at');
            });
        }
    }
};
