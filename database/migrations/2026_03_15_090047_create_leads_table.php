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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable()->index();
            $table->string('website')->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->string('country')->nullable()->index();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('industry')->nullable();
            $table->string('company_size')->nullable();
            $table->string('revenue_range')->nullable();
            $table->string('lead_source')->nullable()->index();
            $table->string('verification_status')->default('pending')->index();
            $table->unsignedTinyInteger('quality_score')->nullable();
            $table->boolean('is_duplicate')->default(false);
            $table->unsignedBigInteger('duplicate_of_lead_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreign('duplicate_of_lead_id')->references('id')->on('leads')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
