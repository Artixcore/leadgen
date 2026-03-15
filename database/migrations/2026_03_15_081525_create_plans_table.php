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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_price_id_monthly')->nullable();
            $table->string('stripe_price_id_yearly')->nullable();
            $table->unsignedInteger('leads_per_month')->nullable();
            $table->unsignedInteger('exports_per_month')->default(0);
            $table->unsignedInteger('saved_lists_count')->default(0);
            $table->unsignedInteger('team_members_limit')->default(0);
            $table->boolean('api_access')->default(false);
            $table->boolean('advanced_filters')->default(false);
            $table->unsignedInteger('trial_days')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
