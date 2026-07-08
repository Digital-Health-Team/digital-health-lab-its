<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Warehouse (admin_gudang) material verification for the check_material
     * pipeline stage. Verified and flagged states are mutually exclusive;
     * both null means the warehouse has not checked the order yet.
     */
    public function up(): void
    {
        Schema::table('service_bookings', function (Blueprint $table) {
            $table->timestamp('material_verified_at')->nullable()->after('current_status');
            $table->foreignId('material_verified_by')->nullable()->after('material_verified_at')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('material_flagged_at')->nullable()->after('material_verified_by');
            $table->text('material_flag_note')->nullable()->after('material_flagged_at');
        });
    }

    public function down(): void
    {
        Schema::table('service_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('material_verified_by');
            $table->dropColumn(['material_verified_at', 'material_flagged_at', 'material_flag_note']);
        });
    }
};
