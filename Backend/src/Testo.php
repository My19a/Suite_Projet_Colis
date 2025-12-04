<?php

require __DIR__ . '/vendor/autoload.php';

use SAE\Auth\CasAuthenticator;
use SAE\Auth\CasConfiguration;

$config = new CasConfiguration(
    host: 'cas.univ-paris13.fr',
    context: '/cas/',
    port: 443,
    caCertPath: '/etc/pki/ca-trust/univ-paris13.fr-chain.pem', // Todo à changer avec celui de Butelle
    serviceBaseUrl: 'http://localhost:8000',
    changeSessionId: true,
);

$authenticator = new CasAuthenticator($config);
$user = $authenticator->authenticate();

$content = sprintf(
    '<p>Bonjour %s (%s)</p>',
    htmlspecialchars($user->getDisplayName(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
    htmlspecialchars($user->getLogin(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
);

printf(
    '<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Accueil</title>
    </head>
    <body>
        <h1>Authentification CAS réussie</h1>
        %s
    </body>
    </html>',
    $content
);
