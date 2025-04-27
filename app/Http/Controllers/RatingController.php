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
    try {
        $booking = Booking::findOrFail($bookingId);
        
        // Check if rating already exists for this booking
        if ($booking->rating()->exists()) {
            return response()->json([
                'message' => 'This booking already has a rating',
            ], 422);
        }

        $rating = $booking->rating()->create([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'message' => 'Rating submitted successfully',
            'data' => $rating,
        ], 201);
        
    } catch (\Exception $e) {
        \Log::error('Rating submission error: ' . $e->getMessage());
        return response()->json([
            'message' => 'An error occurred while submitting the rating',
            'error' => $e->getMessage() // Only in development
        ], 500);
    }
}
}