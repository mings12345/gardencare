<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'booking_id' => 'nullable|exists:bookings,id',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100, // Convert to cents
                'currency' => 'php',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'booking_id' => $request->booking_id,
                    'user_id' => auth()->id(),
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentSucceeded($paymentIntent);
                break;
                
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailed($paymentIntent);
                break;
                
            // Add more event handlers as needed
        }

        return response()->json(['status' => 'success']);
    }

    protected function handlePaymentSucceeded($paymentIntent)
    {
        $metadata = $paymentIntent->metadata;
        
        if (isset($metadata->booking_id)) {
            $booking = Booking::find($metadata->booking_id);
            
            if ($booking) {
                // Update booking payment status
                $booking->update([
                    'payment_status' => 'paid',
                    'payment_method' => $paymentIntent->payment_method_types[0] ?? 'card',
                    'stripe_payment_intent_id' => $paymentIntent->id,
                ]);

                // Create payment record
                Payment::create([
                    'booking_id' => $booking->id,
                    'user_id' => $metadata->user_id,
                    'amount' => $paymentIntent->amount / 100,
                    'payment_method' => $paymentIntent->payment_method_types[0] ?? 'card',
                    'transaction_id' => $paymentIntent->id,
                    'status' => 'succeeded',
                    'currency' => $paymentIntent->currency,
                    'metadata' => json_encode($paymentIntent),
                ]);
            }
        }
    }

    protected function handlePaymentFailed($paymentIntent)
    {
        $metadata = $paymentIntent->metadata;
        
        if (isset($metadata->booking_id)) {
            $booking = Booking::find($metadata->booking_id);
            
            if ($booking) {
                $booking->update([
                    'payment_status' => 'failed',
                ]);

                Payment::create([
                    'booking_id' => $booking->id,
                    'user_id' => $metadata->user_id,
                    'amount' => $paymentIntent->amount / 100,
                    'payment_method' => $paymentIntent->payment_method_types[0] ?? 'card',
                    'transaction_id' => $paymentIntent->id,
                    'status' => 'failed',
                    'currency' => $paymentIntent->currency,
                    'metadata' => json_encode($paymentIntent),
                ]);
            }
        }
    }
}