<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
   public function store(Request $request)
{
    $data = $request->validate([
        'user.fullname' => 'required|string|max:255',
        'user.email' => 'required|email|max:255',
        'payment_method' => 'required|string',
        'items' => 'required|array|min:1',
        'items.*.title' => 'required|string',
        'items.*.monthly_rent' => 'required|numeric',
        'items.*.image_url' => 'nullable|string',
    ]);

    try {
        DB::beginTransaction();

        $booking = Booking::create([
            'fullname' => $data['user']['fullname'],
            'email' => $data['user']['email'],
            'payment_method' => $data['payment_method'],
            'total' => array_sum(array_column($data['items'], 'monthly_rent')),
        ]);

        foreach ($data['items'] as $item) {
            BookingItem::create([
                'booking_id' => $booking->id,
                'title' => $item['title'],
                'city' => $item['city'] ?? null,
                'property_type' => $item['property_type'] ?? null,
                'bedrooms' => $item['bedrooms'] ?? null,
                'bathrooms' => $item['bathrooms'] ?? null,
                'monthly_rent' => $item['monthly_rent'],
                'image_url' => html_entity_decode($item['image_url'] ?? ''),
            ]);
        }

        DB::commit();

        return response()->json(['success' => true, 'booking_id' => $booking->id]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function myBookings()
    {
        // Get the currently logged-in user
        $user = Auth::user();

        // Fetch bookings that belong to the logged-in user
        // Assuming 'fullname' or 'user_id' in bookings table links to the user
        $bookings = Booking::where('email', $user->email)->with('items')->get();

        return view('bookings.my_bookings', compact('bookings'));
    }


      // Show checkout page
    public function checkout()
    {
        return view('checkout'); // Make sure you have resources/views/checkout.blade.php
    }

    
    // Show bookings for logged-in user
    public function index()
{
    $userEmail = Auth::user()->email;

    $bookings = Booking::with('items')
        ->where('email', $userEmail) // ✅ filter by logged-in user's email
        ->orderBy('created_at', 'desc')
        ->get();

    return view('showbooking', compact('bookings'));
}

    // Cancel booking
   // Cancel booking
public function destroy($id)
{
    $booking = Booking::where('id', $id)
        ->where('email', Auth::user()->email)
        ->first();

    if (!$booking) {
        return response()->json([
            'success' => false,
            'message' => 'Booking not found or unauthorized.'
        ], 404);
    }

    // delete related items first
    $booking->items()->delete();
    $booking->delete();

    return response()->json([
        'success' => true,
        'message' => 'Booking cancelled successfully.'
    ]);
}






}
