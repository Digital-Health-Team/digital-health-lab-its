<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_registrations', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'awaiting_verification', 'paid', 'rejected'])
                ->default('unpaid')
                ->after('status');
            $table->string('payment_proof')->nullable()->after('payment_status');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('payment_proof');
        });
    }

    public function down(): void
    {
        Schema::table('training_registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn(['payment_status', 'payment_proof']);
        });
    }
};
