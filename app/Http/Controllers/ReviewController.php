<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    // Store a new review (web)
    public function store(Request $request)
    {
        $review = $this->reviewService->createReview($request);
        return response()->json(['success' => true, 'review' => $review]);
    }
}
