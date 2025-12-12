<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class AdminService
{
    /**
     * Get property statistics
     */
    public function getPropertyStats(): array
    {
        return [
            'total' => Property::count(),
            'houses' => Property::where('property_type', 'House')->count(),
            'apartments' => Property::where('property_type', 'Apartment')->count(),
            'villas' => Property::where('property_type', 'Villa')->count(),
            'avgRent' => Property::avg('monthly_rent') ?? 0,
            'cities' => Property::distinct('city')->count('city'),
        ];
    }

    /**
     * Get all properties with optional image URL formatting
     */
    public function getAllProperties(bool $formatImage = false)
    {
        $properties = Property::all();

        if ($formatImage) {
            $properties->map(fn($p) => tap($p, fn($x) => $x->image_path = asset($x->image_path)));
        }

        return $properties;
    }

    /**
     * Get all bookings with formatted items
     */ public function getAllBookings()
    {
        try {
            $bookings = Booking::with('items')
                ->orderBy('created_at', 'desc')
                ->get();

            $allItems = [];

            foreach ($bookings as $booking) {
                foreach ($booking->items as $item) {
                    $allItems[] = [
                        'booking_id' => $booking->id,
                        'item_id' => $item->id,
                        'fullname' => $booking->fullname,
                        'email' => $booking->email,
                        'payment_method' => $booking->payment_method,
                        'total' => $booking->total,
                        'created_at' => $booking->created_at->toDateTimeString(),
                        'title' => $item->title,
                        'city' => $item->city,
                        'property_type' => $item->property_type,
                        'bedrooms' => $item->bedrooms,
                        'bathrooms' => $item->bathrooms,
                        'monthly_rent' => $item->monthly_rent,
                        'image_url' => $item->image_url,
                    ];
                }
            }

            return $allItems;

        } catch (\Exception $e) {
            Log::error('AdminService getAllBookings error: ' . $e->getMessage());
            return [];
        }
    }
    /**
     * Cancel a booking
     */
    public function cancelBooking($booking, $bookingService)
    {
        try {
            $bookingService->cancelBooking($booking);
            return ['success' => true, 'message' => 'Booking cancelled successfully.'];
        } catch (\Exception $e) {
            Log::error('AdminService cancelBooking error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
