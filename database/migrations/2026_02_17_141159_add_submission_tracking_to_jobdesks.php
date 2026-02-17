<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('jobdesks', function (Blueprint $table) {
            // Waktu staf klik "Done" (sebelum approved PM)
            $table->timestamp('submitted_at')->nullable()->after('deadline_task');
            // Menyimpan durasi telat dalam menit (untuk KPI)
            $table->integer('lateness_minutes')->default(0)->after('submitted_at');
        });
    }

    public function down()
    {
        Schema::table('jobdesks', function (Blueprint $table) {
            $table->dropColumn(['submitted_at', 'lateness_minutes']);
        });
    }
};
