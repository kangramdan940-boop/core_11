<?php

return [
    'paths' => ['v1/*'],
    'allowed_methods' => ['GET', 'HEAD', 'OPTIONS'],
    'allowed_origins' => ['http://localhost:19006', 'http://127.0.0.1:19006', 'http://localhost:8081', 'http://127.0.0.1:8081'],
    'allowed_origins_patterns' => ['^http://localhost:\d+$', '^http://127\.0\.0\.1:\d+$'],
    'allowed_headers' => ['Content-Type', 'Authorization'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];