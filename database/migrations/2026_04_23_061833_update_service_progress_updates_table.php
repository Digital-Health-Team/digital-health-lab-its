<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('service_progress_updates', function (Blueprint $table) {
            // Tambahkan jika belum ada
            if (!Schema::hasColumn('service_progress_updates', 'percentage')) {
                $table->integer('percentage')->default(0)->after('status_label');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
