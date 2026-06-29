<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a hex colour code to the colors lookup table so that
     * the service-request form can render colour swatches.
     */
    public function up(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->string('hex', 7)->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn('hex');
        });
    }
};
