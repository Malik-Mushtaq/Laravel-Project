<?php

namespace App\Http\Controllers;
use App\Models\Property;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;      // ✅ Add this
use App\Models\BookingItem; 

use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        // Fetch all properties
        $properties = Property::all();

        // Calculate stats
        $stats = [
            'total' => Property::count(),
            'houses' => Property::where('property_type', 'House')->count(),
            'apartments' => Property::where('property_type', 'Apartment')->count(),
            'villas' => Property::where('property_type', 'Villa')->count(),
            'avgRent' => Property::avg('monthly_rent') ?? 0,
            'cities' => Property::distinct('city')->count('city'),
        ];

        return view('admin.admindashboard', compact('stats','properties'));
    }

     public function storeProperty(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'title' => 'required|string|max:255',
            'property_type' => 'required|string|max:50',
            'monthly_rent' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'image_url' => 'nullable|url',
            'status' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

       try {
    $property = Property::create([
        'title' => $request->title,
        'property_type' => $request->property_type,
        'monthly_rent' => $request->monthly_rent,
        'bedrooms' => $request->bedrooms,
        'bathrooms' => $request->bathrooms,
        'city' => $request->city,
        'description' => $request->description,
        'image_url' => $request->image_url,
        'status' => $request->status,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Property saved successfully!',
        'property' => $property
    ]);
} catch (\Exception $e) {
    return response()->json([
        'success' => false,
        'errors' => ['exception' => $e->getMessage()]
    ], 500);
}

    }



    public function dashboard()
    {
        return view('admin.admindashboard');
    }

    public function getProperties()
    {
        $properties = Property::all();

        return response()->json($properties);
    }

   

    public function createProperty()
    {
        return view('admin.property_form');
    }

    public function editProperty($id)
    {
        return view('admin.property_edit', ['id' => $id]);
    }

    // Show all bookings
    public function bookings()
    {
        $bookings = Booking::with('items')->orderBy('created_at', 'desc')->get();
        return view('admin.adminbookings', compact('bookings'));
    }

    // Delete a booking
    public function destroyBooking($id)
{
    $booking = Booking::with('items')->find($id);

    if (!$booking) {
        return response()->json([
            'success' => false,
            'message' => 'Booking not found.'
        ], 404);
    }

    // Delete related items first
    $booking->items()->delete();
    $booking->delete();

    return response()->json([
        'success' => true,
        'message' => 'Booking deleted successfully.'
    ]);
}


}
