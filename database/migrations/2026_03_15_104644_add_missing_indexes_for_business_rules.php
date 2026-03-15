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
            $table->index('status');
            $table->index('deleted_at');
        });
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index('ends_at');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->index('paid_at');
        });
        Schema::table('saved_filters', function (Blueprint $table) {
            $table->index(['user_id', 'created_at']);
        });
        Schema::table('lead_import_runs', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex(['ends_at']);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['paid_at']);
        });
        Schema::table('saved_filters', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
        });
        Schema::table('lead_import_runs', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
};
