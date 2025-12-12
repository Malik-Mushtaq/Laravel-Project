<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewService
{
    /**
     * Fetch reviews for a property
     */
    public function getReviewsByProperty($propertyId)
    {
        return Review::where('property_id', $propertyId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new review
     */
    public function createReview(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string',
        ]);

        return Review::create($validated);
    }
}
