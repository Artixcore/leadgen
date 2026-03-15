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
        Schema::table('lead_collectors', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('source_name')->nullable()->after('slug');
            $table->string('source_type')->nullable()->index()->after('source_name');
            $table->string('target_service')->nullable()->after('type');
            $table->string('target_niche')->nullable()->after('target_service');
            $table->string('target_country')->nullable()->after('target_niche');
            $table->string('target_city')->nullable()->after('target_country');
            $table->text('keywords')->nullable()->after('target_city');
            $table->json('filters_json')->nullable()->after('config_encrypted');
            $table->unsignedTinyInteger('trust_score')->nullable()->after('filters_json');
            $table->unsignedTinyInteger('priority')->default(0)->after('trust_score');
            $table->boolean('is_active')->default(true)->after('status');
            $table->timestamp('next_run_at')->nullable()->after('last_run_at');
        });

        Schema::table('lead_collectors', function (Blueprint $table) {
            $table->foreignId('lead_source_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_collectors', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'source_name', 'source_type', 'target_service', 'target_niche',
                'target_country', 'target_city', 'keywords', 'filters_json',
                'trust_score', 'priority', 'is_active', 'next_run_at',
            ]);
        });

        Schema::table('lead_collectors', function (Blueprint $table) {
            $table->foreignId('lead_source_id')->nullable(false)->change();
        });
    }
};
