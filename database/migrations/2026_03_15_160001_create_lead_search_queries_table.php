<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_search_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('query');
            $table->json('parsed_query_json')->nullable();
            $table->string('target_service')->nullable();
            $table->string('target_niche')->nullable();
            $table->string('target_country')->nullable();
            $table->string('target_city')->nullable();
            $table->json('filters_json')->nullable();
            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('total_results')->default(0);
            $table->unsignedInteger('search_took_ms')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_search_queries');
    }
};
