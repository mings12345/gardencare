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

  public function index(): JsonResponse
  {
      try {
          $ratings = Rating::with(['booking.gardener', 'booking.homeowner'])
              ->orderBy('created_at', 'desc')
              ->get()
              ->map(function ($rating) {
                  return [
                      'id' => $rating->id,
                      'booking_id' => $rating->booking_id,
                      'rating' => $rating->rating,
                      'feedback' => $rating->feedback,
                      'created_at' => $rating->created_at,
                      'gardener' => [
                          'id' => $rating->booking->gardener->id,
                          'name' => $rating->booking->gardener->name,
                      ],
                      'homeowner' => [
                          'id' => $rating->booking->homeowner->id,
                          'name' => $rating->booking->homeowner->name,
                      ],
                  ];
              });

          return response()->json([
              'ratings' => $ratings,
          ]);

      } catch (\Exception $e) {
          \Log::error('Failed to fetch ratings: ' . $e->getMessage());
          return response()->json([
              'message' => 'Failed to fetch ratings',
              'error' => $e->getMessage() // Only include in development
          ], 500);
      }
  }
}