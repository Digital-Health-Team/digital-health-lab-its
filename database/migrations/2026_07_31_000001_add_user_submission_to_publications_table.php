<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            // Defaults to 'pending' so a write path that forgets to set it fails closed.
            $table->string('status')->default('pending')->after('category');
            $table->foreignId('validated_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
        });

        // Every row that exists today came from /admin/publications or the seeder and is
        // already public. Leaving it at the new 'pending' default would unpublish the whole
        // research catalogue. user_id stays NULL — these are site content, not submissions.
        DB::table('publications')->update(['status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('validated_by');
            $table->dropColumn('status');
        });
    }
};
