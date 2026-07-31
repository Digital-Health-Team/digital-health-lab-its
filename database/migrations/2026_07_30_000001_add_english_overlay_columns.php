<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * English copy for the domain content the landing page and dashboard read.
 *
 * Indonesian stays in the base column; English goes in `<column>_en`, the same
 * convention as `lab_team_sections.label_en` and `lab_team_people.role_en`.
 * All nullable — App\Traits\HasEnglishOverlay falls back to the Indonesian base
 * when a row has not been translated, so this migration needs no backfill.
 *
 * User-generated content is deliberately excluded: booking_messages.body,
 * service_bookings.brief_description, training_registrations.additional_notes,
 * issue_reports.description and service_progress_updates.notes are what a person
 * actually wrote, not site copy to be translated.
 */
return new class extends Migration
{
    /**
     * Column => whether the `_en` mirror is a json column.
     *
     * @var array<string, array<string, bool>>
     */
    private array $tables = [
        'services' => ['name' => false, 'description' => false],
        'products' => ['name' => false, 'description' => false],
        'events' => [
            'name' => false,
            'theme_title' => false,
            'subtitle' => false,
            'description' => false,
            'location' => false,
        ],
        'open_source_projects' => [
            'title' => false,
            'caption' => false,
            'description' => true,
            'highlights' => true,
            'includes' => true,
        ],
        'publications' => [
            'title' => false,
            'abstract' => false,
            'description' => true,
            'keywords' => true,
        ],
        'trainings' => [
            'title' => false,
            'subtitle' => false,
            'description' => false,
            'location' => false,
            'instructor_title' => false,
            'instructor_bio' => false,
            'what_you_will_learn' => true,
            'includes' => true,
            'curriculum' => true,
        ],
        'teams' => ['name' => false, 'course_name' => false],
        'projects' => ['title' => false],
    ];

    public function up(): void
    {
        foreach ($this->tables as $table => $columns) {
            Schema::table($table, function (Blueprint $t) use ($table, $columns) {
                foreach ($columns as $column => $isJson) {
                    if (Schema::hasColumn($table, $column.'_en')) {
                        continue;
                    }

                    // `description` on services/products/events/trainings is TEXT, so its
                    // mirror is too — a varchar would silently truncate a long translation.
                    $isJson
                        ? $t->json($column.'_en')->nullable()
                        : $t->text($column.'_en')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table => $columns) {
            // Only drop what is actually there. MySQL DDL is not transactional, so a
            // single missing column would otherwise abort the rollback part-way and
            // leave the schema stranded between the two states.
            $present = array_values(array_filter(
                array_map(fn (string $c) => $c.'_en', array_keys($columns)),
                fn (string $c) => Schema::hasColumn($table, $c),
            ));

            if ($present) {
                Schema::table($table, fn (Blueprint $t) => $t->dropColumn($present));
            }
        }
    }
};
