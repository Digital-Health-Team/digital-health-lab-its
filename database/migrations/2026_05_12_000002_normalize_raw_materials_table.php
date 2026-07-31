<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replace string columns (lab_type, category, brand, color) with
     * foreign keys to master lookup tables. Add composite unique index
     * to prevent duplicate stock entries for the same physical item.
     */
    public function up(): void
    {
        // Drop old string columns
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropColumn(['lab_type', 'category', 'brand', 'color']);
        });

        // Add FK columns with composite unique index
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->foreignId('lab_id')->after('id')->constrained('labs')->cascadeOnDelete();
            $table->foreignId('material_category_id')->after('lab_id')->constrained('material_categories')->cascadeOnDelete();
            $table->foreignId('brand_id')->after('material_category_id')->constrained('brands')->cascadeOnDelete();
            $table->foreignId('color_id')->after('brand_id')->constrained('colors')->cascadeOnDelete();

            $table->unique(['lab_id', 'material_category_id', 'brand_id', 'color_id'], 'raw_materials_composite_unique');
        });
    }

    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropUnique('raw_materials_composite_unique');
            $table->dropConstrainedForeignId('color_id');
            $table->dropConstrainedForeignId('brand_id');
            $table->dropConstrainedForeignId('material_category_id');
            $table->dropConstrainedForeignId('lab_id');
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->string('lab_type')->after('id');
            $table->string('category')->after('lab_type');
            $table->string('brand')->after('category');
            $table->string('color')->after('brand');
        });
    }
};
