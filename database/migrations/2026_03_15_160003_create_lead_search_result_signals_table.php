<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_search_result_signals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_search_result_id')->constrained()->cascadeOnDelete();
            $table->string('signal_key');
            $table->string('signal_value')->nullable();
            $table->integer('score_impact')->default(0);
            $table->text('explanation')->nullable();
            $table->timestamps();

            $table->index('lead_search_result_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_search_result_signals');
    }
};
