<?php

return [
    'env' => getenv('APP_ENV') ?: 'development',
    'base_url' => getenv('APP_BASE_URL') ?: 'http://localhost:8000',

    'cas' => [
        'host' => getenv('CAS_HOST') ?: 'cas.univ-paris13.fr',
        'context' => getenv('CAS_CONTEXT') ?: '/cas/',
        'port' => (int) (getenv('CAS_PORT') ?: 443),
        'ca_cert_path' => getenv('CAS_CA_CERT') ?: null,
    ],

    'database' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'suivi_colis_sae',
        'user' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: 'root',
        'charset' => 'utf8mb4',
    ],

    'session' => [
        'name' => 'SAE_COLIS_SESSION',
        'lifetime' => 3600,
    ],

    'admin_uids' => array_filter(
        explode(',', getenv('ADMIN_UIDS') ?: ''),
        fn($uid) => !empty(trim($uid))
    ),
];
