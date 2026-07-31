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
        Schema::table('lab_team_people', function (Blueprint $table) {
            $table->json('completed_projects')->nullable()->after('expertise');
            $table->json('education')->nullable()->after('completed_projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_team_people', function (Blueprint $table) {
            $table->dropColumn(['completed_projects', 'education']);
        });
    }
};
