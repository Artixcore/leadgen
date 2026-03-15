<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_search_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('provider_class');
            $table->string('source_type')->nullable();
            $table->string('status', 20)->default('active');
            $table->unsignedInteger('priority')->default(0);
            $table->json('config_json')->nullable();
            $table->unsignedTinyInteger('trust_score')->default(50);
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_search_providers');
    }
};
