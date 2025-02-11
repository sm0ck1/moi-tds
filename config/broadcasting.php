<?php

return [
    'default' => env('BROADCAST_DRIVER', 'pusher'),
    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('VITE_PUSHER_APP_KEY', ''),
            'secret' => env('VITE_PUSHER_APP_SECRET', ''),
            'app_id' => env('VITE_PUSHER_APP_ID', ''),
            'options' => [
                'cluster' => env('VITE_PUSHER_APP_CLUSTER', 'eu'),
                'useTLS'  => true,
                'timeout' => 30,
                'persistent' => true,
                'encrypted' => true,
            ],
        ],
    ],
];
