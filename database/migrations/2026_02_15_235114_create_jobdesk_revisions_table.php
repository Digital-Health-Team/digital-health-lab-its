<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jobdesk_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobdesk_id')->constrained('jobdesks')->cascadeOnDelete();
            $table->foreignId('pm_id')->constrained('users'); // User yang merevisi
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobdesk_revisions');
    }
};
