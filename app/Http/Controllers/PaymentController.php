<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $amount = $request->amount;
        $platformFee = $amount * 0.10;  // Example: 10% platform fee
        $netAmount = $amount - $platformFee;

        $payment = Payment::create([
            'booking_id' => $request->booking_id,
            'homeowner_id' => $request->homeowner_id,
            'gardener_id' => $request->gardener_id,
            'amount' => $amount,
            'platform_fee' => $platformFee,
            'net_amount' => $netAmount,
            'status' => 'pending',
        ]);

        // Redirect to PayPal Payment URL
        return response()->json([
            'approval_url' => 'https://paypal.com/checkout-url-example',
        ]);
    }

    public function capturePayment(Request $request)
    {
        $payment = Payment::findOrFail($request->payment_id);
        $payment->update(['status' => 'completed']);

        // Distribute payment
        // Assuming ₱1000 -> ₱900 to Gardener, ₱100 to Admin
        return response()->json(['message' => 'Payment captured successfully!']);
    }
}
