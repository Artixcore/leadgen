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
        Schema::rename('lead_lead_list', 'lead_list_items');

        Schema::table('lead_list_items', function (Blueprint $table) {
            $table->unique(['lead_id', 'lead_list_id'], 'lead_list_items_lead_id_lead_list_id_unique');
        });

        Schema::table('lead_tag', function (Blueprint $table) {
            $table->unique(['lead_id', 'tag_id'], 'lead_tag_lead_id_tag_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_list_items', function (Blueprint $table) {
            $table->dropUnique('lead_list_items_lead_id_lead_list_id_unique');
        });
        Schema::table('lead_tag', function (Blueprint $table) {
            $table->dropUnique('lead_tag_lead_id_tag_id_unique');
        });
        Schema::rename('lead_list_items', 'lead_lead_list');
    }
};
