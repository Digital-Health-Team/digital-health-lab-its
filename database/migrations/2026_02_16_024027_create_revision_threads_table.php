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
        Schema::create('revision_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobdesk_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // Pengirim pesan
            $table->text('content');
            $table->boolean('is_staff_reply')->default(false); // Penanda: true=staff, false=pm/admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revision_threads');
    }
};
