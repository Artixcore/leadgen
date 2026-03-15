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
        Schema::create('raw_lead_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_collector_id')->constrained('lead_collectors')->cascadeOnDelete();
            $table->foreignId('lead_collector_run_id')->nullable()->constrained('lead_collector_runs')->nullOnDelete();
            $table->string('source_record_id')->nullable()->index();
            $table->string('company_name')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('niche')->nullable();
            $table->string('source_url')->nullable();
            $table->json('raw_payload');
            $table->json('normalized_payload')->nullable();
            $table->string('verification_status')->nullable();
            $table->string('processing_status')->default('pending')->index();
            $table->string('dedupe_hash')->nullable()->index();
            $table->timestamp('discovered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_lead_records');
    }
};
