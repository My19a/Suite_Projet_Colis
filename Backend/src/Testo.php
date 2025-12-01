<?php

require __DIR__ . '/vendor/autoload.php';

use SAE\Auth\CasAuthenticator;
use SAE\Auth\CasConfiguration;

$config = new CasConfiguration(
    host: 'cas.univ-paris13.fr',
    context: '/cas/',
    port: 443,
    caCertPath: '/etc/pki/ca-trust/univ-paris13.fr-chain.pem'
);

$authenticator = new CasAuthenticator($config);
$user = $authenticator->authenticate();

echo sprintf('Bonjour %s (%s)', $user->getDisplayName(), $user->getLogin());
