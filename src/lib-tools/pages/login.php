<?php

require_once __DIR__ . '/../Auth/User.php';
require_once __DIR__ . '/../Auth/CasUser.php';
require_once __DIR__ . '/../Auth/CasConfiguration.php';
require_once __DIR__ . '/../Auth/CasAuthenticator.php';
require_once __DIR__ . '/../../public/models/Model.php';
require_once __DIR__ . '/../../public/models/UserRepository.php';

$config = require __DIR__ . '/../bootstrap.php';

// Si déjà connecté, rediriger
if (isset($_SESSION['user']) && $_SESSION['user'] instanceof User) {
    $role = $_SESSION['user']->getRole();
    $redirects = [
        'admin' => '/admin/dashboard',
        'responsable_colis' => '/postal/dashboard',
        'demandeur' => '/departement/dashboard',
        'editeur_bc' => '/finance/dashboard',
    ];
    header('Location: ' . ($redirects[$role] ?? '/'));
    exit;
}

// En mode dev, rediriger vers dev-login
if ($config['env'] === 'development') {
    header('Location: /dev-login');
    exit;
}

// Si le bouton CAS est cliqué, déclencher l'authentification
if (isset($_GET['auth']) && $_GET['auth'] === 'cas') {
    $casConfig = CasConfiguration::fromArray($config['cas'], $config['base_url']);
    $casAuth = new CasAuthenticator($casConfig);
    $casUser = $casAuth->authenticate();

    $user = UserRepository::findByUidCas($casUser->getLogin());
    if (!$user) {
        $role = in_array($casUser->getLogin(), $config['admin_uids'] ?? []) ? 'admin' : 'demandeur';
        $user = UserRepository::create($casUser->getLogin(), $casUser->getAttributes(), $role);
    }

    $_SESSION['user'] = $user;
    $_SESSION['authenticated'] = true;

    $redirects = [
        'admin' => '/admin/dashboard',
        'responsable_colis' => '/postal/dashboard',
        'demandeur' => '/departement/dashboard',
        'editeur_bc' => '/finance/dashboard',
    ];
    header('Location: ' . ($redirects[$user->getRole()] ?? '/'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion – Suivi Colis</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
    <style>
        .page-auth { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .auth-box { width: 100%; max-width: 400px; }
        .auth-marque { text-align: center; margin-bottom: 20px; }
        .auth-logo { margin: 0 auto 10px; display: flex; justify-content: center; }
        .auth-logo img { width: 84px; height: 84px; object-fit: contain; }
        .auth-titre { font-size: 22px; font-weight: 650; color: var(--princ); letter-spacing: -0.3px; }
        .auth-sous { font-size: 13px; color: var(--texte-doux); margin-top: 2px; }
        .auth-carte { background: var(--surface); border: 1px solid var(--bord); border-radius: var(--r); padding: 24px; }
        .auth-bouton { width: 100%; padding: 11px 16px; font-size: 14px; }
        .auth-aide { margin-top: 12px; font-size: 12px; color: var(--texte-leger); text-align: center; }
        .auth-pied { text-align: center; margin-top: 18px; font-size: 11.5px; color: var(--texte-leger); }
    </style>
</head>
<body class="page-auth">
    <main class="auth-box">
        <div class="auth-marque">
            <div class="auth-logo"><img src="/assets/img/logo-colis.png" alt="Suivi Colis"></div>
            <h1 class="auth-titre">Suivi Colis</h1>
            <p class="auth-sous">IUT de Villetaneuse — Sorbonne Paris Nord</p>
        </div>

        <div class="auth-carte">
            <div class="message message-info" style="margin-bottom: 18px;">
                <span class="message-corps">Connectez-vous avec vos identifiants universitaires.</span>
            </div>

            <a href="/login?auth=cas" class="bouton bouton-principal auth-bouton">
                <svg class="icone" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Se connecter avec CAS
            </a>

            <p class="auth-aide">Authentification centralisée de l'université.</p>
        </div>

        <p class="auth-pied">© <?= date('Y') ?> IUT de Villetaneuse — Suivi Colis</p>
    </main>
</body>
</html>
