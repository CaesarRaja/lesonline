<?php

return [
    'default' => env('REVERB_DRIVER', 'reverb'),
    'drivers' => [
        'reverb' => [
            'app_id' => env('REVERB_APP_ID'),
            'app_key' => env('REVERB_APP_KEY'),
            'app_secret' => env('REVERB_APP_SECRET'),
            'options' => [
                'host' => env('REVERB_HOST', 'localhost'),
                'port' => env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
                'use_tls' => env('REVERB_SCHEME', 'http') === 'https',
            ],
            'client_options' => [],
        ],
    ],
];
