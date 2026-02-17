<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('check_in_latitude')->nullable()->after('check_in');
            $table->string('check_in_longitude')->nullable()->after('check_in_latitude');
            $table->string('check_out_latitude')->nullable()->after('check_out');
            $table->string('check_out_longitude')->nullable()->after('check_out_latitude');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['check_in_latitude', 'check_in_longitude', 'check_out_latitude', 'check_out_longitude']);
        });
    }
};
