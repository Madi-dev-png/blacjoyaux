<?php

return [
    // Configuration de l'assistant IA (API Groq)
   'groq' => [
    'key'   => env('GROQ_API_KEY'),
    'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
],

'brand' => [
    'whatsapp' => env('BJ_WHATSAPP', '2250700000000'),
],
];
