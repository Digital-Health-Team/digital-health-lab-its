<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refactor: Add lab_type, brand, color for hierarchical material grouping.
     * Drop legacy 'name' column — materials are now identified by category+brand+color.
     */
    public function up(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            // Lab separation: tekkes vs praktikum
            $table->string('lab_type')->after('id');

            // Hierarchical grouping columns
            $table->string('brand')->after('category');
            $table->string('color')->after('brand');
        });

        // Drop the old flat 'name' column (replaced by category/brand/color composite)
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropColumn(['lab_type', 'brand', 'color']);
        });
    }
};
