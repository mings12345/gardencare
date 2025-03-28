<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createIntent(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:1',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $booking = Booking::find($request->booking_id);

        if (!$booking->isPayable()) {
            return response()->json(['error' => 'Booking is not payable'], 400);
        }

        $intent = PaymentIntent::create([
            'amount' => $request->amount * 100,
            'currency' => 'php',
            'metadata' => [
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
            ],
        ]);

        $booking->update(['payment_intent_id' => $intent->id]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'payment_intent_id' => $intent->id,
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required',
            'booking_id' => 'required|exists:bookings,id',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));
        $intent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status !== 'succeeded') {
            return response()->json(['error' => 'Payment not completed'], 400);
        }

        $booking = Booking::find($request->booking_id);
        $amount = $intent->amount / 100;

        $booking->update([
            'amount_paid' => $booking->amount_paid + $amount,
            'status' => $booking->payment_type === 'full' && $amount >= $booking->total_price
                ? 'paid'
                : 'partially_paid',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'amount' => $amount,
            'payment_method' => 'stripe',
            'payment_intent_id' => $intent->id,
            'status' => 'completed',
        ]);

        return response()->json([
            'message' => 'Payment successful',
            'booking' => $booking,
        ]);
    }

    public function paymentHistory(Request $request)
    {
        $payments = Payment::where('user_id', auth()->id())
            ->with('booking')
            ->latest()
            ->get();

        return response()->json($payments);
    }
}