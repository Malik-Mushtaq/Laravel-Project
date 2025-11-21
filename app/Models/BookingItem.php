<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'title',
        'city',
        'property_type',
        'bedrooms',
        'bathrooms',
        'monthly_rent',
        'image_url',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}


?>