<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitRatingRequest;
use App\Models\Rating; // Ensure you have created a Rating model
use App\Models\Booking; // Ensure you have a Booking model as well
use Illuminate\Http\JsonResponse;

class RatingController extends Controller
{
    public function submitRating(SubmitRatingRequest $request, $bookingId): JsonResponse
    {
        // Optional: Check if Booking exists
        $booking = Booking::findOrFail($bookingId);

        // Create a new rating
        $rating = Rating::create([
            'booking_id' => $bookingId,
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'message' => 'Rating submitted successfully',
            'data' => $rating,
        ], 201);
    }
}