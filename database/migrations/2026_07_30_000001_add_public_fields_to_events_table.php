<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Presentation fields for the public /events surface. All nullable so the
     * existing admin CRUD (name / year / theme_title) keeps working untouched.
     *
     * No timestamps() — Event sets `public $timestamps = false`.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('subtitle')->nullable()->after('theme_title');
            $table->text('description')->nullable()->after('subtitle');
            $table->string('thumbnail_url')->nullable()->after('description');
            $table->dateTime('starts_at')->nullable()->after('thumbnail_url');
            $table->dateTime('ends_at')->nullable()->after('starts_at');
            $table->string('location')->nullable()->after('ends_at');
            $table->string('category')->nullable()->after('location');
            $table->string('registration_url')->nullable()->after('category');
            $table->boolean('is_featured')->default(false)->after('registration_url');
        });

        // slug is the route key — existing rows need one before /events/{slug} works.
        foreach (DB::table('events')->select('id', 'name', 'year')->get() as $event) {
            DB::table('events')
                ->where('id', $event->id)
                ->update(['slug' => Str::slug($event->name.'-'.$event->year)]);
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn([
                'slug',
                'subtitle',
                'description',
                'thumbnail_url',
                'starts_at',
                'ends_at',
                'location',
                'category',
                'registration_url',
                'is_featured',
            ]);
        });
    }
};
