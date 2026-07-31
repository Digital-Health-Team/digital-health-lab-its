<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Inventories = reusable tools/assets (NOT consumables).
     * Separated from raw_materials which are single-use consumables.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('lab_type'); // tekkes, praktikum
            $table->string('name');
            $table->string('brand');
            $table->integer('total_quantity')->default(0);
            $table->integer('available_quantity')->default(0); // Decremented on checkout, incremented on return
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
