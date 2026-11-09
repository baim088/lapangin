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
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('venues')->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sport_category');
            $table->string('surface_type');
            $table->json('specs')->nullable();
            $table->unsignedBigInteger('price_regular');
            $table->unsignedBigInteger('price_prime');
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index('venue_id');
            $table->index('sport_category');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
