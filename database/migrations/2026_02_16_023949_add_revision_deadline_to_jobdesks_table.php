<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jobdesks', function (Blueprint $table) {
            $table->dateTime('deadline_revision')->nullable()->after('deadline_task');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobdesks', function (Blueprint $table) {
            //
        });
    }
};
