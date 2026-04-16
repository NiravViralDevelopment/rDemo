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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->enum('type', ['house', 'shop'])->index();
            $table->string('title', 255);
            $table->string('city', 120)->nullable();
            $table->string('address', 255)->nullable();
            $table->unsignedInteger('bedrooms')->nullable(); // houses only
            $table->unsignedInteger('area_sqft')->nullable();
            $table->decimal('price_per_day', 10, 2)->default(0);
            $table->enum('status', ['available', 'booked', 'inactive'])->default('available')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
