<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReviewService;

class ReviewApiController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    // Fetch all reviews for a property
    public function index($propertyId)
    {
        $reviews = $this->reviewService->getReviewsByProperty($propertyId);
        return response()->json(['success' => true, 'reviews' => $reviews]);
    }

    // Store a new review
    public function store(Request $request)
    {
        $review = $this->reviewService->createReview($request);
        return response()->json(['success' => true, 'review' => $review]);
    }
}
