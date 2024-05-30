<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom configuration for billing
    |--------------------------------------------------------------------------
    |
    */
    'phone' => env('BILLING_PHONE', ''),
    'email' => env('BILLING_EMAIL', ''),
    'address' => env('BILLING_ADDRESS', ''),
    'mpesa' => [
        'show' => env('BILLING_MPESA_SHOW', false),
        'buy_goods' => env('BILLING_MPESA_BUY_GOODS', ''),
        'name' => env('BILLING_MPESA_NAME')
    ],
    'tax' => [
        'show' => env('BILLING_TAX_SHOW', FALSE),
        'vat' => [
            'rate' => env('BILLING_TAX_VAT_RATE', 0),
            'type' => env('BILLING_TAX_VAT_TYPE', 'inclusive'),
        ]
    ],
    "currency" => env("BILLING_CURRENCY", "KES"),
    "setup" => [
        "key" => env("BILLING_SETUP_KEY"),
    ]
];
