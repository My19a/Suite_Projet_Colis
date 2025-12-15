<?php

namespace SAE\Controllers;

use SAE\Auth\{User, AuthMiddleware};

class AuthController
{
    private User $currentUser;

    public function __construct(User $user)
    {
        $this->currentUser = $user;
    }

    /**
     * Déconnexion de l'utilisateur
     * Détruit la session et déconnecte du CAS
     */
    public function logout(): void
    {
        AuthMiddleware::logout();

        // Si phpCAS est bien initialisé, il redirigera automatiquement
        // Sinon, redirection manuelle
        redirect('/');
    }
}
