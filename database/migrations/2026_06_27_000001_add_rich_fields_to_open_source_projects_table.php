<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('open_source_projects', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('title');
            $table->string('caption', 500)->nullable()->after('slug');
            $table->string('listing_type')->nullable()->after('category'); // journals/products/powerpoint/downloadable/read_only
            $table->json('description')->nullable()->after('listing_type');
            $table->json('highlights')->nullable()->after('description');
            $table->string('cover_color')->nullable()->after('highlights');
            $table->boolean('is_featured')->default(false)->after('cover_color');
            $table->string('license')->nullable()->default('MIT')->after('is_featured');
            $table->string('version')->nullable()->after('license');
            $table->string('format')->nullable()->after('version');
            $table->json('includes')->nullable()->after('format');
        });
    }

    public function down(): void
    {
        Schema::table('open_source_projects', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'caption', 'listing_type', 'description',
                'highlights', 'cover_color', 'is_featured',
                'license', 'version', 'format', 'includes',
            ]);
        });
    }
};
