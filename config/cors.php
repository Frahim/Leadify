<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*'], // Define the paths that should allow CORS (like your API routes)

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],

    // Allow your Chrome extension's origin (replace with the actual origin of your extension)
    'allowed_origins' => ['chrome-extension://*', 'http://127.0.0.1:8000'],
 
    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Allow all headers

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // Set to true if you want to allow cookies

];
