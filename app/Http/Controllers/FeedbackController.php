<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Store a newly created feedback in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $bookingId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $bookingId)
    {
        // Validate the request
        $validatedData = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        // Find the booking
        $booking = Booking::findOrFail($bookingId);

        // Check if the authenticated user is the homeowner of this booking
        if (Auth::id() != $booking->homeowner_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if the booking is completed
        if ($booking->status != 'completed') {
            return response()->json(['message' => 'Feedback can only be submitted for completed bookings'], 400);
        }

        // Check if feedback already exists
        $existingFeedback = Feedback::where('booking_id', $bookingId)->first();
        if ($existingFeedback) {
            return response()->json(['message' => 'Feedback already submitted for this booking'], 400);
        }

        // Create new feedback
        $feedback = Feedback::create([
            'booking_id' => $bookingId,
            'homeowner_id' => Auth::id(),
            'gardener_id' => $booking->gardener_id,
            'rating' => $validatedData['rating'],
            'feedback' => $validatedData['feedback'],
        ]);

        // Update gardener's average rating
        $this->updateGardenerRating($booking->gardener_id);

        return response()->json([
            'message' => 'Feedback submitted successfully',
            'feedback' => $feedback
        ], 201);
    }

    /**
     * Update the gardener's average rating.
     *
     * @param  int  $gardenerId
     * @return void
     */
    private function updateGardenerRating($gardenerId)
    {
        $avgRating = Feedback::where('gardener_id', $gardenerId)->avg('rating');
        
        // Assuming you have a gardener_profiles table with an avg_rating column
        \DB::table('gardener_profiles')
            ->where('user_id', $gardenerId)
            ->update(['avg_rating' => $avgRating]);
    }
}