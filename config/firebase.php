<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Project Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for your Firebase project.
    |
    */

    'project_id' => env('FIREBASE_PROJECT_ID', 'gardencare-2c63e'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Service Account Credentials
    |--------------------------------------------------------------------------
    |
    | Path to your Firebase service account JSON file.
    | This should be stored securely in your storage/app directory.
    |
    */
    'credentials' => [
        'file' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase-service-account.json')),
        
        // Alternatively, you can specify the credentials directly:
        // 'credentials' => [
        //     'type' => 'service_account',
        //     'project_id' => env('FIREBASE_PROJECT_ID'),
        //     'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
        //     'private_key' => str_replace("\\n", "\n", env('FIREBASE_PRIVATE_KEY')),
        //     'client_email' => env('FIREBASE_CLIENT_EMAIL'),
        //     'client_id' => env('FIREBASE_CLIENT_ID'),
        //     'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
        //     'token_uri' => 'https://oauth2.googleapis.com/token',
        //     'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
        //     'client_x509_cert_url' => env('FIREBASE_CLIENT_CERT_URL'),
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Services Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the Firebase services you want to use.
    |
    */
    'services' => [
        'messaging' => [
            'default_ttl' => '1 hour', // Default time-to-live for messages
            'default_android_channel' => [
                'channel_id' => 'default',
                'name' => 'Default Channel',
                'importance' => 'default',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Database URL
    |--------------------------------------------------------------------------
    |
    | Only needed if you're using Firebase Realtime Database.
    |
    */
    'database_url' => env('FIREBASE_DATABASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Storage Bucket
    |--------------------------------------------------------------------------
    |
    | Only needed if you're using Firebase Storage.
    |
    */
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Auth Configuration
    |--------------------------------------------------------------------------
    |
    | Only needed if you're using Firebase Authentication.
    |
    */
    'auth' => [
        'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Options
    |--------------------------------------------------------------------------
    |
    | Configure the HTTP client used by the Firebase SDK.
    |
    */
    'http_client_options' => [
        'timeout' => 30, // Request timeout in seconds
        'proxy' => env('FIREBASE_HTTP_PROXY'), // Proxy server if needed
        'verify' => env('FIREBASE_VERIFY_SSL', true), // SSL verification
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how Firebase SDK caches data.
    |
    */
    'cache' => [
        'store' => env('FIREBASE_CACHE_STORE', 'file'),
        'prefix' => env('FIREBASE_CACHE_PREFIX', 'firebase'),
    ],
];