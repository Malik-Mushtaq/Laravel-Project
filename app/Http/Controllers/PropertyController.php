<?php

namespace App\Http\Controllers;
 use App\Models\Property;

use Illuminate\Http\Request;

class PropertyController extends Controller
{   
    public function destroy($id)
{
    $property = Property::findOrFail($id);
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

    // Update property in DB
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'required|string|max:50',
            'monthly_rent' => 'required|numeric',
            'bedrooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'city' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        $property = Property::findOrFail($id);
        $property->update([
            'title' => $request->title,
            'property_type' => $request->property_type,
            'monthly_rent' => $request->monthly_rent,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'city' => $request->city,
            'description' => $request->description,
            'image_url' => $request->image_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Property updated successfully!'
        ]);
    }


    public function landing()
    {
        return view('landing');
    }

    public function index()
    {
        // Fetch all properties from database
        $properties = Property::all();

        return view('property', compact('properties'));
    }

    
        public function show($id)
        {
            // Fetch the property by its ID
            $property = Property::with('reviews')->findOrFail($id);

            // Pass the property to the Blade view
            return view('show', compact('property'));
        }

    public function cart(){
        return view('cart');
    }
}
