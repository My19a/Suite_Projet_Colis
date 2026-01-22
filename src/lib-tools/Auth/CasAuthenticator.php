<?php

final class CasAuthenticator
{
    private CasConfiguration $configuration;
    private bool $phpCasBootstrapped = false;

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
        $displayName = $attributes['displayName'] ?? $attributes['cn'] ?? $attributes['givenName'] ?? $login;
        $email = $attributes['mail'] ?? null;

        return new CasUser($login, $displayName, $email, $attributes);
    }

    private function ensurePhpCasAvailable(): void
    {
        if (!class_exists('\\phpCAS')) {
            throw new \Error('phpCAS not available. Run "composer install".');
        }
    }

    private function bootstrapPhpCas(): void
    {
        if ($this->phpCasBootstrapped) return;

        \phpCAS::client(
            CAS_VERSION_2_0,
            $this->configuration->getHost(),
            $this->configuration->getPort(),
            $this->configuration->getContext(),
            $this->configuration->getServiceBaseUrl(),
            $this->configuration->shouldChangeSessionId()
        );

        $caCertPath = $this->configuration->getCaCertPath();
        if ($caCertPath !== null) {
            \phpCAS::setCasServerCACert($caCertPath);
        } else {
            \phpCAS::setNoCasServerValidation();
        }
        \phpCAS::setLang(PHPCAS_LANG_FRENCH);

        $this->phpCasBootstrapped = true;
    }

    public static function logout(): void
    {
        if (class_exists('\\phpCAS') && \phpCAS::isInitialized()) {
            \phpCAS::logout();
        }
    }
}
