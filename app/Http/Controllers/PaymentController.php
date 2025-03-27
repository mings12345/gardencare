<?php

namespace App\Http\Controllers;

use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymongoService;
    
    public function __construct(PayMongoService $paymongoService)
    {
        $this->paymongoService = $paymongoService;
    }
    
    public function createIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string',
            'booking_id' => 'required|exists:bookings,id'
        ]);
        
        try {
            $response = $this->paymongoService->createPaymentIntent(
                $request->amount,
                $request->description,
                ['booking_id' => $request->booking_id]
            );
            
            return response()->json($response['data']);
        } catch (\Exception $e) {
            Log::error('Payment Intent Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function attachMethod(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'payment_method_id' => 'required|string',
            'return_url' => 'required|url'
        ]);
        
        try {
            $response = $this->paymongoService->attachPaymentMethod(
                $request->payment_intent_id,
                $request->payment_method_id,
                $request->return_url
            );
            
            return response()->json($response['data']);
        } catch (\Exception $e) {
            Log::error('Payment Attach Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function handleWebhook(Request $request)
    {
        $signature = $request->header('Paymongo-Signature');
        $payload = $request->getContent();
        
        if (!$this->paymongoService->verifyWebhook($signature, $payload)) {
            Log::error('Invalid webhook signature');
            return response()->json(['error' => 'Invalid signature'], 403);
        }
        
        $event = json_decode($payload, true)['data'];
        
        // Process different event types
        switch ($event['attributes']['type']) {
            case 'payment.paid':
                $this->handlePaymentSuccess($event);
                break;
            case 'payment.failed':
                $this->handlePaymentFailed($event);
                break;
        }
        
        return response()->json(['success' => true]);
    }
    
    protected function handlePaymentSuccess($event)
    {
        $payment = $event['attributes']['data'];
        $bookingId = $payment['attributes']['metadata']['booking_id'] ?? null;
        
        if ($bookingId) {
            // Update booking status
            $booking = \App\Models\Booking::find($bookingId);
            if ($booking) {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed'
                ]);
                
                // Create payment record
                \App\Models\Payment::create([
                    'booking_id' => $bookingId,
                    'amount' => $payment['attributes']['amount'] / 100,
                    'payment_method' => $payment['attributes']['payment_method']['type'],
                    'reference_id' => $payment['id'],
                    'status' => 'completed'
                ]);
                
                // Send notification
            }
        }
    }
    
    protected function handlePaymentFailed($event)
    {
        // Implement failure handling
    }

    public function processPayment(Request $request)
{
    $request->validate([
        'payment_intent_id' => 'required|string',
        'payment_method' => 'required|array',
        'return_url' => 'required|url'
    ]);

    try {
        // Create payment method first
        $paymentMethod = $this->createPaymentMethod($request->payment_method);
        
        // Then attach to payment intent
        $response = $this->paymongoService->attachPaymentMethod(
            $request->payment_intent_id,
            $paymentMethod['id'],
            $request->return_url
        );

        return response()->json($response['data']);
    } catch (\Exception $e) {
        Log::error('Payment Processing Error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

protected function createPaymentMethod($cardData)
{
    $client = new Client();
    
    $response = $client->post('https://api.paymongo.com/v1/payment_methods', [
        'auth' => [config('services.paymongo.secret_key'), ''],
        'json' => [
            'data' => [
                'attributes' => [
                    'type' => 'card',
                    'details' => [
                        'card_number' => str_replace(' ', '', $cardData['number']),
                        'exp_month' => (int) explode('/', $cardData['expiry'])[0],
                        'exp_year' => (int) '20' . explode('/', $cardData['expiry'])[1],
                        'cvc' => $cardData['cvc'],
                    ],
                    'billing' => [
                        'name' => $cardData['name'],
                        'email' => auth()->user()->email,
                    ]
                ]
            ]
        ]
    ]);

    return json_decode($response->getBody(), true)['data'];
}
}