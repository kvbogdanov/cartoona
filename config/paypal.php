<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => 'sandbox', // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'username'    => env('PAYPAL_SANDBOX_API_USERNAME', 'dm-facilitator@dioxy.ru'),
        'password'    => env('PAYPAL_SANDBOX_API_PASSWORD', ''),
        'secret'      => env('PAYPAL_SANDBOX_API_SECRET', 'Ae9Y7Z-k1B2Gh2SbfiQudpSX1hTQHC58zChhniJbEt6FlYdcCMHS2DIdpdH9GCa_xIxJ9UN4NQN458so'),
        'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', 'EDITjFPofpRT_VFxR2UWsZD3Gbgm-WL9dg6y6czGqg_jgsqBkWAkI6alE6LJ7u-zIiwq1bzX14XF0v7c'),
        'app_id'      => 'APP-80W284485P519543T', // Used for testing Adaptive Payments API in sandbox mode
    ],
    'live' => [
        'username'    => env('PAYPAL_LIVE_API_USERNAME', ''),
        'password'    => env('PAYPAL_LIVE_API_PASSWORD', ''),
        'secret'      => env('PAYPAL_LIVE_API_SECRET', ''),
        'certificate' => env('PAYPAL_LIVE_API_CERTIFICATE', ''),
        'app_id'      => '', // Used for Adaptive Payments API
    ],

    'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => 'USD',
    'billing_type'   => 'MerchantInitiatedBilling',
    'notify_url'     => '', // Change this accordingly for your application.
    'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => true, // Validate SSL when creating api client.
];

