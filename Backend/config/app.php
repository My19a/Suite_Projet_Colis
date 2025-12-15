<?php

return [
    'env' => getenv('APP_ENV') ?: 'production',
    'base_url' => getenv('APP_BASE_URL') ?: 'http://localhost:8000',

    'cas' => [
        'host' => 'cas.univ-paris13.fr',
        'context' => '/cas/',
        'port' => 443,
        'ca_cert_path' => null, // TODO: Ajouter le certificat CA en production
    ],

    'database' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'sae_colis',
        'user' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
    ],

    'session' => [
        'name' => 'SAE_SESSION',
        'lifetime' => 3600, // 1 heure
    ],

    // UIDs CAS des administrateurs
    'admin_uids' => ['admin1', 'admin2'],
];
