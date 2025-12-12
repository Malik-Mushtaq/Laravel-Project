<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Services\PropertyService;

class PropertyController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $this->propertyService->updateProperty($request, $property);

        return response()->json(['success' => true, 'message' => 'Property updated successfully!']);
    }

    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $this->propertyService->deleteProperty($property);

        return response()->json(['success' => true, 'message' => 'Property deleted successfully!']);
    }

    public function search(Request $request)
    {
        $query = Property::query();

        if ($request->filled('city')) $query->where('city', 'LIKE', "%{$request->city}%");
        if ($request->filled('type')) $query->where('property_type', $request->type);
        if ($request->filled('bedrooms')) {
    if ($request->bedrooms === '4+') {
        $query->where('bedrooms', '>=', 4);
    } else {
        $query->where('bedrooms', $request->bedrooms);
    }
}

        if ($request->filled('sort')) {
            $query->orderBy('monthly_rent', $request->sort === 'low_high' ? 'asc' : 'desc');
        }

        $properties = $query->get()->map(fn($p) => $p->image_path = asset($p->image_path) ?: $p);

        return response()->json($properties);
    }
    public function landing()
{
    return view('landing'); // resources/views/landing.blade.php
}

public function index()
{
    $properties = Property::leftJoin('booking_items', 'properties.id', '=', 'booking_items.property_id')
        ->whereNull('booking_items.property_id')
        ->select('properties.*')
        ->get();

    return view('property', compact('properties'));
}
public function show($id)
{
    $property = Property::with('reviews')->findOrFail($id);
    return view('show', compact('property'));
}
public function cart()
{
    return view('cart');
}

public function edit($id)
    {
        return view('admin.property_edit', ['id' => $id]);
    }

}

