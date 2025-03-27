<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    protected $client;
    protected $secretKey;
    
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.paymongo.com/v1/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
        
        $this->secretKey = config('services.paymongo.secret_key');
    }
    
    public function createPaymentIntent($amount, $description, $metadata = [])
    {
        try {
            $response = $this->client->post('payment_intents', [
                'auth' => [$this->secretKey, ''],
                'json' => [
                    'data' => [
                        'attributes' => [
                            'amount' => $amount * 100,
                            'payment_method_allowed' => ['card', 'gcash', 'grab_pay'],
                            'currency' => 'PHP',
                            'description' => $description,
                            'metadata' => $metadata
                        ]
                    ]
                ]
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('PayMongo Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function attachPaymentMethod($paymentIntentId, $paymentMethodId, $returnUrl)
    {
        try {
            $response = $this->client->post("payment_intents/$paymentIntentId/attach", [
                'auth' => [$this->secretKey, ''],
                'json' => [
                    'data' => [
                        'attributes' => [
                            'payment_method' => $paymentMethodId,
                            'return_url' => $returnUrl
                        ]
                    ]
                ]
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('PayMongo Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function verifyWebhook($signature, $payload)
    {
        $computedSignature = hash_hmac('sha256', $payload, config('services.paymongo.webhook_secret'));
        return hash_equals($signature, $computedSignature);
    }
}