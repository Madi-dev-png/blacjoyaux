<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Assistant IA — API Anthropic (Claude)
    |--------------------------------------------------------------------------
    | Clé à renseigner dans .env : ANTHROPIC_API_KEY
    | (https://console.anthropic.com/)
    */
    'anthropic' => [
        'key'   => env('ANTHROPIC_API_KEY'),
        'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-6'),
    ],

    'brand' => [
        'whatsapp' => env('BJ_WHATSAPP', '2250153864606'),
    ],
];
