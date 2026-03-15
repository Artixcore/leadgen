<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status', 20)->default('active')->after('remember_token');
            $table->timestamp('status_changed_at')->nullable()->after('status');
            $table->foreignId('status_changed_by')->nullable()->constrained('users')->nullOnDelete()->after('status_changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['status_changed_by']);
            $table->dropColumn(['status', 'status_changed_at', 'status_changed_by']);
        });
    }
};
