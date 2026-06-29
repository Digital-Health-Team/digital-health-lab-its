<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->primary(['user_id', 'role_id']);
        });

        // Seed from existing users.role_id so every user immediately has at least one switchable role
        DB::statement('INSERT INTO user_roles (user_id, role_id) SELECT id, role_id FROM users WHERE role_id IS NOT NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
