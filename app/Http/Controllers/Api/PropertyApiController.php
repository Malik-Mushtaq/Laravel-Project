<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Services\PropertyService;

class PropertyApiController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    // Show all unbooked properties (Blade view)
    public function index()
    {
        $properties = Property::leftJoin('booking_items', 'properties.id', '=', 'booking_items.property_id')
            ->whereNull('booking_items.property_id')
            ->select('properties.*')
            ->get()
            ->map(function($p) {
                $p->image_path = asset($p->image_path);
                return $p;
            });

        return view('property', compact('properties'));
    }

    // Show single property
    public function show($id)
    {
        $property = Property::with('reviews')->findOrFail($id);
        $property->image_path = asset($property->image_path);
        return response()->json(['success' => true, 'property' => $property]);
    }

    // Search/filter unbooked properties (JSON API)
    public function search(Request $request)
    {
        $query = Property::leftJoin('booking_items', 'properties.id', '=', 'booking_items.property_id')
    ->whereNull('booking_items.property_id')
    ->select('properties.*');

if ($request->filled('city')) {
    $query->where('properties.city', 'LIKE', "%{$request->city}%");
}
if ($request->filled('type')) {
    $query->where('properties.property_type', $request->type);
}
if ($request->filled('bedrooms')) {
    $request->bedrooms === '4+' 
        ? $query->where('properties.bedrooms', '>=', 4) 
        : $query->where('properties.bedrooms', $request->bedrooms);
}
if ($request->filled('sort')) {
    $query->orderBy('properties.monthly_rent', $request->sort === 'low_high' ? 'asc' : 'desc');
}

        $properties = $query->get()->map(function($p) {
            $p->image_path = asset($p->image_path);
            return $p;
        });

        return response()->json(['success' => true, 'properties' => $properties]);
    }
}
