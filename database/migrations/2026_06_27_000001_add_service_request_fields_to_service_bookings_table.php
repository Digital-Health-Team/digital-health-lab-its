<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_bookings', function (Blueprint $table) {
            $table->string('reference_photo_path')->nullable()->after('brief_description');
            $table->string('model_file_path')->nullable()->after('reference_photo_path');
            $table->string('material_preference')->nullable()->after('model_file_path');
            $table->string('filament_width')->nullable()->after('material_preference');
            $table->string('scan_purpose')->nullable()->after('filament_width');
            $table->json('object_dimensions')->nullable()->after('scan_purpose');
        });
    }

    public function down(): void
    {
        Schema::table('service_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'reference_photo_path',
                'model_file_path',
                'material_preference',
                'filament_width',
                'scan_purpose',
                'object_dimensions',
            ]);
        });
    }
};
