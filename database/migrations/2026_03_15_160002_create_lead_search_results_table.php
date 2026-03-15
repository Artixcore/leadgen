<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_search_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_search_query_id')->constrained()->cascadeOnDelete();
            $table->string('source_name');
            $table->string('source_type')->nullable();
            $table->string('company_name');
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('niche')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->unsignedTinyInteger('trust_score')->default(0);
            $table->unsignedInteger('relevance_score')->default(0);
            $table->unsignedInteger('opportunity_score')->default(0);
            $table->string('verification_status', 20)->nullable();
            $table->text('explanation')->nullable();
            $table->text('recommended_pitch')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->index('lead_search_query_id');
            $table->index(['lead_search_query_id', 'relevance_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_search_results');
    }
};
