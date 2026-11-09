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
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('address');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->time('open_time')->default('08:00:00');
            $table->time('close_time')->default('23:00:00');
            $table->time('prime_start_time')->default('17:00:00');
            $table->time('prime_end_time')->default('23:00:00');
            $table->unsignedInteger('slot_lock_duration_minutes')->default(10);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
