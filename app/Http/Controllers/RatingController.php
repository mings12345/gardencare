<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitRatingRequest;
use App\Models\Rating;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;

class RatingController extends Controller
{
    public function submitRating(SubmitRatingRequest $request, $bookingId): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            
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
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            $ratings = Rating::with([
                    'booking.gardener',
                    'booking.homeowner',
                    'booking.serviceProvider'
                ])
                ->when($user->user_type === 'service_provider', function($q) use ($user) {
                    $q->whereHas('booking', function($q) use ($user) {
                        $q->where('serviceprovider_id', $user->id);
                    });
                })
                ->when($user->user_type === 'gardener', function($q) use ($user) {
                    $q->whereHas('booking', function($q) use ($user) {
                        $q->where('gardener_id', $user->id);
                    });
                })
                ->when($user->user_type === 'homeowner', function($q) use ($user) {
                    $q->whereHas('booking', function($q) use ($user) {
                        $q->where('homeowner_id', $user->id);
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($rating) {
                    return [
                        'id' => $rating->id,
                        'booking_id' => $rating->booking_id,
                        'rating' => $rating->rating,
                        'feedback' => $rating->feedback,
                        'created_at' => $rating->created_at,
                        'gardener' => $rating->booking->gardener ? [
                            'id' => $rating->booking->gardener->id,
                            'name' => $rating->booking->gardener->name,
                        ] : null,
                        'homeowner' => $rating->booking->homeowner ? [
                            'id' => $rating->booking->homeowner->id,
                            'name' => $rating->booking->homeowner->name,
                        ] : null,
                        'service_provider' => $rating->booking->serviceProvider ? [
                            'id' => $rating->booking->serviceProvider->id,
                            'name' => $rating->booking->serviceProvider->name,
                        ] : null,
                    ];
                });

            return response()->json([
                'ratings' => $ratings,
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch ratings: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch ratings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}