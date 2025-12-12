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
            $table->integer('property_id')->unique();
            $table->string('title');
            $table->string('property_type');
            $table->decimal('monthly_rent', 10, 2);
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->string('city');
            $table->text('description');
            $table->string('image_path')->nullable(); // instead of storing huge base64 in DB
            $table->string('status')->nullable();
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
