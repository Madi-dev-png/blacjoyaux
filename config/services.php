<?php

return [
    // Configuration de l'assistant IA (API Anthropic)
    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
        'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-6'),
    ],

    // Coordonnées de contact de la marque
    'brand' => [
        'whatsapp' => env('BJ_WHATSAPP', '2250700000000'),
    ],
];
