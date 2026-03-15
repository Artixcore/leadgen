<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plan_usages', function (Blueprint $table) {
            $table->unsignedInteger('lead_search_count')->default(0)->after('exports_count');
        });
    }

    public function down(): void
    {
        Schema::table('plan_usages', function (Blueprint $table) {
            $table->dropColumn('lead_search_count');
        });
    }
};
