<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BookingService
{
    public function createBooking(Request $request)
    {
        $data = $request->validate([
            'user.fullname' => 'required|string|max:255',
            'user.email' => 'required|email|max:255',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string',
            'items.*.monthly_rent' => 'required|numeric',
            'items.*.id' => 'required|integer',
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
                    'property_id'=> $item['id'],
                    'property_type' => $item['property_type'] ?? null,
                    'bedrooms' => $item['bedrooms'] ?? null,
                    'bathrooms' => $item['bathrooms'] ?? null,
                    'monthly_rent' => $item['monthly_rent'],
                    'image_url' => html_entity_decode($item['image_url'] ?? ''),
                ]);
            }

            DB::commit();

            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cancelBooking(Booking $booking)
    {
        $booking->items()->delete();
        $booking->delete();
        return true;
    }

    public function cancelBookingItem($itemId)
{
    $item = \App\Models\BookingItem::findOrFail($itemId);
    $booking = $item->booking;

    $item->delete(); // delete only this item

    // If no items remain, delete the booking
    if ($booking->items()->count() === 0) {
        $booking->delete();
    }

    return true;
}

}
