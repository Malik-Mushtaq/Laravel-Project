<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string',
        ]);

        $review = Review::create([
            'property_id' => $request->property_id,
            'name' => $request->name,
            'rating' => $request->rating,
            'text' => $request->text,
        ]);

        return response()->json(['success' => true, 'review' => $review]);
    }
}
