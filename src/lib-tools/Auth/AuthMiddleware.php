<?php

class AuthMiddleware
{
    private CasAuthenticator $authenticator;
    private array $config;

    public function __construct(CasAuthenticator $authenticator, array $config)
    {
        $this->authenticator = $authenticator;
        $this->config = $config;
    }

    public function handle(): User
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name($this->config['session']['name'] ?? 'SAE_SESSION');
            session_start();
        }

        if (isset($_SESSION['user']) && $_SESSION['user'] instanceof User) {
            return $_SESSION['user'];
        }

        if ($this->config['env'] === 'development') {
            return $this->handleDevAuth();
        }

        $casUser = $this->authenticator->authenticate();
        $user = UserRepository::findByUidCas($casUser->getLogin());

        if (!$user) {
            $user = $this->createUserFromCas($casUser);
        }

        $_SESSION['user'] = $user;
        $_SESSION['authenticated'] = true;
        $_SESSION['uid_cas'] = $casUser->getLogin();

        return $user;
    }

    private function createUserFromCas(CasUser $casUser): User
    {
        $role = 'demandeur';
        if (in_array($casUser->getLogin(), $this->config['admin_uids'] ?? [])) {
            $role = 'admin';
        }

        return UserRepository::create($casUser->getLogin(), $casUser->getAttributes(), $role);
    }

    private function handleDevAuth(): User
    {
        if (isset($_SESSION['dev_uid_cas'])) {
            $user = UserRepository::findByUidCas($_SESSION['dev_uid_cas']);
            if ($user) {
                $_SESSION['user'] = $user;
                return $user;
            }
        }

        header('Location: /dev-login.php');
        exit;
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        CasAuthenticator::logout();
    }
}
