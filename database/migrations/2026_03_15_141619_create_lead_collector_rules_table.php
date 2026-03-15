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
        Schema::create('lead_collector_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_collector_id')->constrained('lead_collectors')->cascadeOnDelete();
            $table->string('rule_key')->index();
            $table->string('rule_operator');
            $table->text('rule_value')->nullable();
            $table->integer('score_weight')->default(0);
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_collector_rules');
    }
};
