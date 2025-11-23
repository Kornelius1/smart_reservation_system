<?php
// config/doku.php

return [
    'client_id' => env('DOKU_CLIENT_ID'),
    'secret_key' => env('DOKU_SECRET_KEY'),
    
    // Tambahkan base_url untuk Sandbox dan Produksi
    'base_url' => env('DOKU_ENV', 'sandbox') === 'production' 
        ? 'https://api.doku.com' 
        : 'https://api-sandbox.doku.com',

    'service_fee' => env('DOKU_SERVICE_FEE', 4440),
];