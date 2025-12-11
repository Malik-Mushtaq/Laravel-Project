<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\BookingItem;

class PropertyController extends Controller
{
    public function destroy($id)
    {
        $property = Property::findOrFail($id);

        // Delete old image if exists
        if ($property->image_path && File::exists(public_path($property->image_path))) {
            File::delete(public_path($property->image_path));
        }

        $property->delete();

        return response()->json([
            'success' => true,
            'message' => 'Property deleted successfully!'
        ]);
    }

    public function edit($id)
    {
        $property = Property::findOrFail($id);
        return view('admin.property_edit', compact('property'));
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'property_type' => 'required|string|max:50',
            'monthly_rent' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'city' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'status' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle new image
            if ($request->hasFile('image_file')) {
                // Delete old image if exists
                if ($property->image_path && File::exists(public_path($property->image_path))) {
                    File::delete(public_path($property->image_path));
                }

                $file = $request->file('image_file');
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

                // Save in public/assets/properties
                $file->move(public_path('assets/properties'), $filename);
                $property->image_path = 'assets/properties/' . $filename;
            }

            $property->title = $request->title;
            $property->property_type = $request->property_type;
            $property->monthly_rent = $request->monthly_rent;
            $property->bedrooms = $request->bedrooms;
            $property->bathrooms = $request->bathrooms;
            $property->city = $request->city;
            $property->description = $request->description;
            $property->status = $request->status;
            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'Property updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function landing()
    {
        return view('landing');
    }

    public function index()
    {
       // Get all property IDs that are already booked
    $properties = Property::leftJoin('booking_items', 'properties.id', '=', 'booking_items.property_id')
    ->whereNull('booking_items.property_id')
    ->select('properties.*')
    ->get();

    return view('property', compact('properties'));
    }

    public function show($id)
    {
        $property = Property::with('reviews')->findOrFail($id);
        return view('show', compact('property'));
    }

    public function cart()
    {
        return view('cart');
    }
}
