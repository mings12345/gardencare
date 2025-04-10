<?php

use App\Models\Rating;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500'
        ]);

        // Check if user is the homeowner for this booking
        if (Auth::id() !== $booking->homeowner_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if rating already exists
        if ($booking->rating) {
            return response()->json(['message' => 'This booking already has a rating'], 400);
        }

        $rating = Rating::create([
            'booking_id' => $booking->id,
            'homeowner_id' => $booking->homeowner_id,
            'gardener_id' => $booking->gardener_id,
            'rating' => $request->rating,
            'feedback' => $request->feedback
        ]);

        return response()->json($rating, 201);
    }

    public function gardenerRatings(User $gardener)
    {
        $ratings = Rating::where('gardener_id', $gardener->id)
            ->with('homeowner')
            ->get();

        return response()->json($ratings);
    }

    public function show(Booking $booking)
    {
        $rating = Rating::where('booking_id', $booking->id)->first();
        return response()->json($rating);
    }

    public function update(Request $request, Rating $rating)
    {
        $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500'
        ]);

        // Check if user is the homeowner who created this rating
        if (Auth::id() !== $rating->homeowner_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $rating->update($request->only(['rating', 'feedback']));

        return response()->json($rating);
    }
}
