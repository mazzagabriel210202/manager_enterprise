<?php

use App\Models\Enterprise;

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'sanctum'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'enterprises'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'enterprises',
        ],

        'api' => [
            'driver' => 'sanctum',
            'provider' => 'enterprises',
        ],
    ],

    'providers' => [
        'enterprises' => [
            'driver' => 'eloquent',
            'model' => Enterprise::class,
        ],
    ],

    'passwords' => [
        'enterprises' => [
            'provider' => 'enterprises',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];