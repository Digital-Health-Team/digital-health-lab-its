<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add traceability FKs: link movements to reimbursements (purchases)
     * and progress_updates (lab process consumption).
     */
    public function up(): void
    {
        Schema::table('raw_material_movements', function (Blueprint $table) {
            // Link to reimbursement record when type='in' (material purchase)
            $table->foreignId('reimbursement_id')
                ->nullable()
                ->after('quantity')
                ->constrained('reimbursements')
                ->nullOnDelete();

            // Link to specific progress step when type='out' (lab consumption)
            $table->foreignId('progress_update_id')
                ->nullable()
                ->after('service_booking_id')
                ->constrained('service_progress_updates')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_material_movements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reimbursement_id');
            $table->dropConstrainedForeignId('progress_update_id');
        });
    }
};
