<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingItemsTable extends Migration
{
    public function up(): void
{
    Schema::create('booking_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
        $table->string('title');
        $table->string('city')->nullable();
        $table->string('property_type')->nullable();
        $table->integer('bedrooms')->nullable();
        $table->integer('bathrooms')->nullable();
        $table->decimal('monthly_rent', 10, 2)->nullable();
        $table->text('image_url')->nullable();
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('booking_items');
    }
}
