<?php

namespace App\Http\Controllers;

use App\Services\PayPalService;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    // Create Payment
    public function createPayment(Request $request)
    {
        $amount = $request->amount; // Example: ₱1000
        $response = $this->paypalService->createOrder($amount);

        if ($response->statusCode === 201) {
            Transaction::create([
                'order_id' => $response->result->id,
                'status' => 'pending',
                'amount' => $amount
            ]);

            return response()->json(['approval_url' => $response->result->links[1]->href]);
        }

        return response()->json(['error' => 'Unable to create payment']);
    }

    // Capture Payment
    public function capturePayment(Request $request)
    {
        $orderId = $request->orderID;
        $response = $this->paypalService->captureOrder($orderId);

        if ($response->statusCode === 201) {
            Transaction::where('order_id', $orderId)->update(['status' => 'completed']);

            return response()->json(['success' => true, 'data' => $response->result]);
        }

        return response()->json(['error' => 'Payment capture failed']);
    }

    // Refund Payment
    public function refundPayment(Request $request)
    {
        $captureId = $request->capture_id;
        $amount = $request->amount;

        $response = $this->paypalService->refundPayment($captureId, $amount);

        if ($response->statusCode === 201) {
            return response()->json(['success' => true, 'data' => $response->result]);
        }

        return response()->json(['error' => 'Refund failed']);
    }
}
