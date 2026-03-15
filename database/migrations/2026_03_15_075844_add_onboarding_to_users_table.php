<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('onboarding_completed_at')->nullable()->after('updated_at');
            $table->string('company_name')->nullable()->after('onboarding_completed_at');
            $table->string('phone')->nullable()->after('company_name');
            $table->string('timezone', 50)->nullable()->after('phone');
        });

        DB::table('users')->whereNull('onboarding_completed_at')->update(['onboarding_completed_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['onboarding_completed_at', 'company_name', 'phone', 'timezone']);
        });
    }
};
