<?php

return [
    // Base URL to your unofficial WhatsApp gateway (e.g. http://localhost:3000)
    'gateway_url' => env('WA_GATEWAY_URL', ''),

    // Bearer token or shared secret for the gateway
    'gateway_token' => env('WA_GATEWAY_TOKEN', ''),

    // Request timeout (seconds)
    'timeout' => env('WA_GATEWAY_TIMEOUT', 10),
];
