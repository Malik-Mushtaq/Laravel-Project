<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname', 'email', 'payment_method', 'total',
    ];

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }
}
