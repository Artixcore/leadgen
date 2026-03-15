<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedInteger('lead_search_searches_per_month')->nullable()->after('advanced_filters');
            $table->unsignedInteger('saved_lead_searches_limit')->default(0)->after('lead_search_searches_per_month');
            $table->boolean('lead_search_full_contact')->default(false)->after('saved_lead_searches_limit');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'lead_search_searches_per_month',
                'saved_lead_searches_limit',
                'lead_search_full_contact',
            ]);
        });
    }
};
