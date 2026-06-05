<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tracks who borrowed which inventory item and when it was returned.
     * ended_at = null means the item is still checked out.
     */
    public function up(): void
    {
        Schema::create('inventory_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();

            // Optional link to a service booking (lab session context)
            $table->foreignId('service_booking_id')
                ->nullable()
                ->constrained('service_bookings')
                ->nullOnDelete();

            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable(); // null = still in use
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_usages');
    }
};
