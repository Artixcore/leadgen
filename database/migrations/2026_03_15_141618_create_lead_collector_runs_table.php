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
        Schema::create('lead_collector_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_collector_id')->constrained('lead_collectors')->cascadeOnDelete();
            $table->string('run_type')->default('manual')->index();
            $table->string('status')->default('pending')->index();
            $table->unsignedInteger('total_found')->default(0);
            $table->unsignedInteger('total_processed')->default(0);
            $table->unsignedInteger('total_new')->default(0);
            $table->unsignedInteger('total_duplicates')->default(0);
            $table->unsignedInteger('total_failed')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('error_message')->nullable();
            $table->json('meta_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_collector_runs');
    }
};
