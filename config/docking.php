<?php

/**
 * DocKing configuration
 */
return [
    'console-enabled' => env('DOCKING_CONSOLE_ENABLED', false),
    'console-password' => env('DOCKING_CONSOLE_PASSWORD', ''),

    'public-access-key' => env('DOCKING_PUBLIC_ACCESS_KEY', ''),

    'default-pdf-driver' => env('DOCKING_DEFAULT_PDF_DRIVER', 'gotenberg'),

    'drivers' => [
        'gotenberg' => [
            'endpoint' => env('DOCKING_GOTENBERG_ENDPOINT', null),
        ],
    ],
];
