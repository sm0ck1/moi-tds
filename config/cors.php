<?php

return [
    'paths' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        env('APP_URL') . ':5174',
        env('APP_URL'),
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [
        'X-Inertia'
    ],
    'max_age' => 0,
    'supports_credentials' => true,
];
