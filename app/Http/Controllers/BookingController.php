<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Services\BookingService;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function store(Request $request)
    {
        try {
            $booking = $this->bookingService->createBooking($request);
            return response()->json(['success' => true, 'booking_id' => $booking->id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $userEmail = Auth::user()->email;
        $bookings = Booking::with('items')->where('email', $userEmail)->orderBy('created_at', 'desc')->get();

        return view('showbooking', compact('bookings'));
    }

    public function myBookings()
    {
        $userEmail = Auth::user()->email;
        $bookings = Booking::with('items')->where('email', $userEmail)->get();

        return view('bookings.my_bookings', compact('bookings'));
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function destroy($id)
    {
        $booking = Booking::where('id', $id)->where('email', Auth::user()->email)->firstOrFail();
        $this->bookingService->cancelBooking($booking);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully.'
        ]);
    }
}
