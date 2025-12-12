<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Booking;
use App\Services\PropertyService;
use App\Services\BookingService;
use App\Services\AdminService;

class AdminController extends Controller
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

    // Dashboard
    public function index()
    {
        $properties = $this->adminService->getAllProperties();
        $stats = $this->adminService->getPropertyStats();
        return view('admin.admindashboard', compact('stats', 'properties'));
    }

    // Property CRUD
    public function createProperty() { return view('admin.property_form'); }
    public function editProperty($id) { return view('admin.property_edit', ['id' => $id]); }

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
            'message' => 'Property saved successfully!',
            'property' => $property
        ]);
    }

    public function updateProperty(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $this->propertyService->updateProperty($request, $property);

        return response()->json([
            'success' => true,
            'message' => 'Property updated successfully!'
        ]);
    }

    public function destroyProperty($id)
    {
        $property = Property::findOrFail($id);
        $this->propertyService->deleteProperty($property);

        return response()->json([
            'success' => true,
            'message' => 'Property deleted successfully!'
        ]);
    }

    // Bookings (Web)
    public function bookings()
    {
        $bookings = $this->adminService->getAllBookings();
        return view('admin.adminbookings', compact('bookings'));
    }

    public function destroyBooking($id)
    {
        $booking = Booking::with('items')->findOrFail($id);
        $result = $this->adminService->cancelBooking($booking, $this->bookingService);

        return response()->json($result);
    }
}
