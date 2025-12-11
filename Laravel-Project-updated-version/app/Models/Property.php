<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

   protected $fillable = [
     'title', 'property_type', 'monthly_rent',
    'bedrooms', 'bathrooms', 'city', 'description', 'image_path', 'status'
];

public function reviews()
{
    return $this->hasMany(Review::class);
}

public function show($id)
{
    $property = Property::with('reviews')->findOrFail($id); // fetch property with reviews
    return view('properties.show', compact('property'));
}

}
