<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            // Drop FKs first — MySQL refuses to drop a unique index referenced by an FK constraint.
            $table->dropForeign(['lab_id']);
            $table->dropForeign(['material_category_id']);
            $table->dropForeign(['color_id']);
            $table->dropUnique('raw_materials_composite_unique');
            $table->dropColumn(['lab_id', 'material_category_id', 'color_id', 'current_stock']);
            $table->string('name')->after('brand_id');
        });
    }

    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->foreignId('lab_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('color_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('current_stock')->default(0);
            $table->unique(
                ['lab_id', 'material_category_id', 'brand_id', 'color_id'],
                'raw_materials_composite_unique'
            );
        });
    }
};
