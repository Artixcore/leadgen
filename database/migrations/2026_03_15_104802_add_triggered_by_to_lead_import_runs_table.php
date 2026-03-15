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
        Schema::table('lead_import_runs', function (Blueprint $table) {
            $table->foreignId('triggered_by')->nullable()->after('lead_source_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_import_runs', function (Blueprint $table) {
            $table->dropForeign(['triggered_by']);
        });
    }
};
