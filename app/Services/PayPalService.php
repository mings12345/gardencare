<?php

namespace App\Services;

use PayPal\Checkout\Orders\OrdersCreateRequest;
use PayPal\Checkout\Orders\OrdersCaptureRequest;
use PayPal\Checkout\Payments\CapturesRefundRequest;
use PayPal\Checkout\Core\SandboxEnvironment;
use PayPal\Checkout\Core\PayPalHttpClient;
use Exception;

class PayPalService
{
    private $client;

    public function __construct()
    {
        $environment = new SandboxEnvironment(
            config('services.paypal.client_id'),
            config('services.paypal.secret')
        );

        $this->client = new PayPalHttpClient($environment);
    }

    // Create Order
    public function createOrder($amount)
    {
        try {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'PHP',
                            'value' => $amount
                        ]
                    ]
                ]
            ];

            return $this->client->execute($request);
        } catch (Exception $e) {
            throw new Exception('PayPal Order Creation Failed: ' . $e->getMessage());
        }
    }

    // Capture Order
    public function captureOrder($orderId)
    {
        try {
            $request = new OrdersCaptureRequest($orderId);
            $request->prefer('return=representation');

            return $this->client->execute($request);
        } catch (Exception $e) {
            throw new Exception('Payment Capture Failed: ' . $e->getMessage());
        }
    }

    // Refund Order
    public function refundPayment($captureId, $amount)
    {
        try {
            $request = new CapturesRefundRequest($captureId);
            $request->body = [
                'amount' => [
                    'currency_code' => 'PHP',
                    'value' => $amount
                ]
            ];

            return $this->client->execute($request);
        } catch (Exception $e) {
            throw new Exception('Refund Failed: ' . $e->getMessage());
        }
    }
}
