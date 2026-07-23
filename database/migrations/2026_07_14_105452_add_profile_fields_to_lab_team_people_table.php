<?php

use App\Models\LabTeamPerson;
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
        Schema::table('lab_team_people', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name_full');
            $table->string('email')->nullable()->after('bio');
            $table->string('linkedin_url')->nullable()->after('email');
            $table->string('instagram_url')->nullable()->after('linkedin_url');
            $table->json('expertise')->nullable()->after('instagram_url');
        });

        // Backfill slugs for people that predate this column.
        LabTeamPerson::whereNull('slug')->get()->each(function (LabTeamPerson $person) {
            $person->update(['slug' => LabTeamPerson::generateUniqueSlug($person->name_full, $person->id)]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_team_people', function (Blueprint $table) {
            $table->dropColumn(['slug', 'email', 'linkedin_url', 'instagram_url', 'expertise']);
        });
    }
};
