<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PropertyService
{
    public function handleImageUpload($file)
    {
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('assets/properties'), $filename);
        return 'assets/properties/' . $filename;
    }

    public function createProperty(Request $request)
    {
        $imagePath = $request->hasFile('image_file') ? $this->handleImageUpload($request->file('image_file')) : null;

        return Property::create([
            'title' => $request->title,
            'property_type' => $request->property_type,
            'monthly_rent' => $request->monthly_rent,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'city' => $request->city,
            'description' => $request->description,
            'image_path' => $imagePath,
            'status' => $request->status,
        ]);
    }

    public function updateProperty(Request $request, Property $property)
    {
        if ($request->hasFile('image_file')) {
            if ($property->image_path && File::exists(public_path($property->image_path))) {
                File::delete(public_path($property->image_path));
            }
            $property->image_path = $this->handleImageUpload($request->file('image_file'));
        }

        $property->fill($request->only([
            'title', 'property_type', 'monthly_rent', 'bedrooms', 'bathrooms', 'city', 'description', 'status'
        ]));

        $property->save();

        return $property;
    }

    public function deleteProperty(Property $property)
    {
        if ($property->image_path && File::exists(public_path($property->image_path))) {
            File::delete(public_path($property->image_path));
        }

        $property->delete();
        return true;
    }
}
