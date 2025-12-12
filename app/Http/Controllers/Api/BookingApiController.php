<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BookingService;
use App\Models\Booking;

class BookingApiController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    // Create a new booking
    public function store(Request $request)
    {
        try {
            $booking = $this->bookingService->createBooking($request);

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'message' => 'Booking created successfully',
                'data' => $booking->load('items')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get bookings by email (no auth required)
    public function index(Request $request)
    {
        try {
            $email = $request->query('email') ?? $request->input('email');

            if (!$email) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $bookings = Booking::with('items')
                ->where('email', $email)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
       public function destroy(Request $request, $id)
    {
        $email = $request->query('email'); // get email from query

        $booking = Booking::where('id', $id)->where('email', $email)->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found.'
            ], 404);
        }

        try {
            $this->bookingService->cancelBooking($booking);
            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyItem(Request $request, $itemId)
{
    try {
        $this->bookingService->cancelBookingItem($itemId);
        return response()->json(['success' => true, 'message' => 'Booking item cancelled successfully.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


}
