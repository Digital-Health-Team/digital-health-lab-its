<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Filament type catalogue for the 3D-printing service.
     * Each row represents one printable material (PLA, PETG, TPU, etc.)
     * with its scientific name, per-gram price, and a short description.
     */
    public function up(): void
    {
        Schema::create('filament_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();          // e.g. PLA, PETG, TPU
            $table->string('name');                    // e.g. PLA
            $table->string('scientific_name');         // e.g. Polylactic Acid
            $table->integer('price_per_gram');         // Rupiah per gram
            $table->text('description')->nullable();   // shown when row is selected
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filament_types');
    }
};
