<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Damage / stock-issue reports: any admin can report a damaged or
     * depleted item (raw material, tool, inventory — or free-form) to the
     * warehouse admins, who are the only ones that resolve them.
     * Photos reuse the polymorphic attachments table.
     */
    public function up(): void
    {
        Schema::create('issue_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->nullableMorphs('reportable');
            $table->string('type');
            $table->text('description');
            $table->string('status')->default('open');
            $table->text('resolution_note')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('reporter_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_reports');
    }
};
