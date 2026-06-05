<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replace string columns (lab_type, brand) with foreign keys
     * to master lookup tables. Brand is nullable for inventories
     * since not all tools/assets have a specific brand.
     */
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['lab_type', 'brand']);
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->foreignId('lab_id')->after('id')->constrained('labs')->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->after('name')->constrained('brands')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('brand_id');
            $table->dropConstrainedForeignId('lab_id');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->string('lab_type')->after('id');
            $table->string('brand')->after('name');
        });
    }
};
