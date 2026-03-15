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
            $table->string('oauth_provider', 50)->nullable()->after('password');
            $table->string('oauth_id')->nullable()->after('oauth_provider');
            $table->string('avatar')->nullable()->after('oauth_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unique(['oauth_provider', 'oauth_id'], 'users_oauth_provider_oauth_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_oauth_provider_oauth_id_unique');
            $table->dropColumn(['oauth_provider', 'oauth_id', 'avatar']);
        });
    }
};
