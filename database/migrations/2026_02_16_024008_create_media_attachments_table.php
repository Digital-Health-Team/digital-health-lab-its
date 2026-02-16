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
        Schema::create('media_attachments', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable'); // attachable_id, attachable_type
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type'); // image/png, application/pdf
            $table->foreignId('uploader_id')->constrained('users'); // Siapa yang upload
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_attachments');
    }
};
