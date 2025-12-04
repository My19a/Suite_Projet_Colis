<?php

namespace SAE\Auth;

use Error;

final class CasAuthenticator
{
    private CasConfiguration $configuration;

    private bool $phpCasBootstrapped = false; // singleton, éviter de init deux fois mdrr cas univ

    public function __construct(CasConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function authenticate(): CasUser
    {
        $this->ensurePhpCasAvailable();
        $this->bootstrapPhpCas();

        if (!\phpCAS::isAuthenticated()) {
            \phpCAS::forceAuthentication();
        }

        $attributes = \phpCAS::getAttributes();
        $login = \phpCAS::getUser();
        $displayName = $attributes['displayName']
            ?? $attributes['cn']
            ?? $attributes['givenName']
            ?? $login;
        $email = $attributes['mail'] ?? null;

        return new CasUser($login, $displayName, $email, $attributes);
    }

    private function ensurePhpCasAvailable(): void
    {
        if (!class_exists('\\phpCAS')) {
            throw new Error(
                'phpCAS library is not available. Install apereo/phpcas and load the autoloader before using the authenticator.'
            );
        }
    }

    private function bootstrapPhpCas(): void
    {
        if ($this->phpCasBootstrapped) {
            return;
        }

        $host = $this->configuration->getHost();
        $port = $this->configuration->getPort();
        $context = $this->configuration->getContext();
        $serviceBaseUrl = $this->configuration->getServiceBaseUrl();
        $changeSessionId = $this->configuration->shouldChangeSessionId();

        \phpCAS::client(CAS_VERSION_2_0, $host, $port, $context, $serviceBaseUrl, $changeSessionId);
/*
        $caCertPath = $this->configuration->getCaCertPath();
        if ($caCertPath !== null) {
            \phpCAS::setCasServerCACert($caCertPath);
        } else {
            \phpCAS::setNoCasServerValidation();
        }
*/
        \phpCas::setNoCasServerValidation(); // attention là je force sans le certificat pem
        \phpCAS::setLang(PHPCAS_LANG_FRENCH);

        $this->phpCasBootstrapped = true;
    }
}
