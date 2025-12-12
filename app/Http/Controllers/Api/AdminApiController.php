<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Booking;
use App\Services\PropertyService;
use App\Services\BookingService;
use App\Services\AdminService;
use Illuminate\Support\Facades\Log;

class AdminApiController extends Controller
{
    protected $propertyService;
    protected $bookingService;
    protected $adminService;

    public function __construct(PropertyService $propertyService, BookingService $bookingService, AdminService $adminService)
    {
        $this->propertyService = $propertyService;
        $this->bookingService = $bookingService;
        $this->adminService = $adminService;
    }

    // Property APIs
    public function stats()
    {
        return response()->json([
            'success' => true,
            'stats' => $this->adminService->getPropertyStats()
        ]);
    }

    public function indexProperties()
    {
        $properties = $this->adminService->getAllProperties(true);
        return response()->json(['success' => true, 'properties' => $properties]);
    }

    public function storeProperty(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'required|string|max:50',
            'monthly_rent' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'image_file' => 'required|image|max:4096',
            'status' => 'nullable|string|max:50',
        ]);

        $property = $this->propertyService->createProperty($request);

        return response()->json([
            'success' => true,
            'message' => 'Property created successfully.',
            'property' => $property
        ]);
    }

    public function updateProperty(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $this->propertyService->updateProperty($request, $property);

        return response()->json([
            'success' => true,
            'message' => 'Property updated successfully.'
        ]);
    }

    public function destroyProperty($id)
    {
        $property = Property::findOrFail($id);
        $this->propertyService->deleteProperty($property);

        return response()->json([
            'success' => true,
            'message' => 'Property deleted successfully.'
        ]);
    }

    // Booking APIs
    public function indexBookings()
    {
        return response()->json([
            'success' => true,
            'data' => $this->adminService->getAllBookings()
        ]);
    }

    public function destroyBooking($id)
    {
        $booking = Booking::with('items')->findOrFail($id);
        $result = $this->adminService->cancelBooking($booking, $this->bookingService);

        return response()->json($result);
    }
}
