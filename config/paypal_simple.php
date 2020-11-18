<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'client_id' => env('PAYPAL_CLIENT_ID','Ae9Y7Z-k1B2Gh2SbfiQudpSX1hTQHC58zChhniJbEt6FlYdcCMHS2DIdpdH9GCa_xIxJ9UN4NQN458so'),
    'secret' => env('PAYPAL_SECRET','EDITjFPofpRT_VFxR2UWsZD3Gbgm-WL9dg6y6czGqg_jgsqBkWAkI6alE6LJ7u-zIiwq1bzX14XF0v7c'),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];
