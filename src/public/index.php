<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lib-tools/Auth/User.php';

// Charger le .env avec phpdotenv (createUnsafeImmutable pour que getenv() fonctionne)
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$config = require __DIR__ . '/../config/app.php';

if ($config['env'] === 'development') {
    error_reporting(E_ALL & ~E_DEPRECATED);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

date_default_timezone_set('Europe/Paris');

if (session_status() === PHP_SESSION_NONE) {
    session_name($config['session']['name']);
    session_start();
}

require_once __DIR__ . '/../lib-tools/Core/Router.php';
require_once __DIR__ . '/../lib-tools/Helpers/helpers.php';
require_once __DIR__ . '/../lib-tools/Auth/User.php';
require_once __DIR__ . '/../lib-tools/Auth/CasUser.php';
require_once __DIR__ . '/../lib-tools/Auth/CasConfiguration.php';
require_once __DIR__ . '/../lib-tools/Auth/CasAuthenticator.php';
require_once __DIR__ . '/../lib-tools/Auth/AuthMiddleware.php';
require_once __DIR__ . '/../lib-tools/Auth/AuthorizationMiddleware.php';

require_once __DIR__ . '/models/Model.php';
require_once __DIR__ . '/models/UserRepository.php';

require_once __DIR__ . '/controllers/ResponsableColisController.php';
require_once __DIR__ . '/controllers/DemandeurController.php';
require_once __DIR__ . '/controllers/EditeurBcController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/TicketController.php';
require_once __DIR__ . '/controllers/PresenceController.php';
require_once __DIR__ . '/models/PresenceModel.php';

$publicRoutes = ['/', '/dev-login', '/login', '/logout', '/accessibilite', '/mentions-legales'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$currentUser = null;

// Handle auth pages routing
if ($uri === '/dev-login' || $uri === '/dev-login.php') {
    require_once __DIR__ . '/../lib-tools/pages/dev-login.php';
    exit;
}

if ($uri === '/logout' || $uri === '/logout.php') {
    require_once __DIR__ . '/../lib-tools/pages/logout.php';
    exit;
}

if ($uri === '/login' || $uri === '/login.php') {
    require_once __DIR__ . '/../lib-tools/pages/login.php';
    exit;
}

if ($uri === '/accessibilite') {
    require __DIR__ . '/views/public/accessibilite.php';
    exit;
}

if ($uri === '/mentions-legales') {
    require __DIR__ . '/views/public/mentions-legales.php';
    exit;
}

if (!in_array($uri, $publicRoutes)) {
    if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof User)) {
        if ($config['env'] === 'development') {
            header('Location: /dev-login');
            exit;
        } else {
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
            $currentUser = $user;
        }
    } else {
        $currentUser = $_SESSION['user'];
    }
}

// Suivi de presence de l'utilisateur connecte (page "Utilisateurs connectes")
if ($currentUser) {
    try {
        (new PresenceModel())->marquerActivite($currentUser->getId());
    } catch (\Throwable $e) {
        // La presence ne doit jamais casser la page
    }
}

// Memorise (par compte) que le tutoriel a ete vu. Appel AJAX du tuto a la fin.
if ($uri === '/tuto/vu' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($currentUser) {
        try {
            $req = Model::getModel()->bd->prepare("UPDATE utilisateur SET tuto_vu = 1 WHERE id_utilisateur = ?");
            $req->execute([$currentUser->getId()]);
        } catch (\Throwable $e) {
            // ne doit jamais casser la navigation
        }
    }
    http_response_code(204);
    exit;
}

$router = new Router();

// Route racine : redirige vers le dashboard approprié selon le rôle
$router->get('/', function() use ($currentUser, $config) {
    if (!$currentUser) {
        // Redirige vers login (qui gère dev vs prod)
        header('Location: /login');
        exit;
    }
    $redirects = [
        'admin' => '/admin/dashboard',
        'responsable_colis' => '/postal/dashboard',
        'demandeur' => '/departement/dashboard',
        'editeur_bc' => '/finance/dashboard',
    ];
    $url = $redirects[$currentUser->getRole()] ?? '/postal/dashboard';
    header('Location: ' . $url);
    exit;
}, null);

$router->get('/postal/dashboard', 'ResponsableColisController', 'dashboard');
$router->get('/postal', 'ResponsableColisController', 'dashboard');
$router->get('/postal/commandes', 'ResponsableColisController', 'commandesAReceptionner');
$router->get('/postal/commande', 'ResponsableColisController', 'detailCommande');
$router->get('/postal/reception', 'ResponsableColisController', 'receptionColis');
$router->post('/postal/reception', 'ResponsableColisController', 'receptionColis');
$router->get('/postal/colis', 'ResponsableColisController', 'listeColis');
$router->get('/postal/transferer', 'ResponsableColisController', 'transfererColis');
$router->get('/postal/historique', 'ResponsableColisController', 'historique');
$router->get('/postal/rechercher-destinataire', 'ResponsableColisController', 'rechercherDestinataire');

// ===== DEPARTEMENT =====
$router->get('/departement', 'DemandeurController', 'dashboard');
$router->get('/departement/dashboard', 'DemandeurController', 'dashboard');
$router->get('/departement/creer-devis', 'DemandeurController', 'creerDevis');
$router->post('/departement/envoyer-devis', 'DemandeurController', 'envoyerDevis');
$router->get('/departement/mes-devis', 'DemandeurController', 'mesDevis');
$router->get('/departement/bons-commande', 'DemandeurController', 'mesBonsCommande');
$router->get('/departement/mes-colis', 'DemandeurController', 'mesColis');
$router->get('/departement/colis/receptionner', 'DemandeurController', 'receptionnerColis');
$router->get('/departement/budget', 'DemandeurController', 'budget');
$router->get('/departement/fournisseurs', 'DemandeurController', 'fournisseurs');

// ===== FINANCE =====
$router->get('/finance', 'EditeurBcController', 'dashboard');
$router->get('/finance/dashboard', 'EditeurBcController', 'dashboard');
$router->get('/finance/valider-devis', 'EditeurBcController', 'validerDevis');
$router->get('/finance/rejeter-devis', 'EditeurBcController', 'rejeterDevis');
$router->get('/finance/devis', 'EditeurBcController', 'devisAVerifier');
$router->get('/finance/voir-devis', 'EditeurBcController', 'voirDevis');
$router->get('/finance/bons-commande', 'EditeurBcController', 'bonsCommande');
$router->get('/finance/budgets', 'EditeurBcController', 'budgets');
$router->get('/finance/historique', 'EditeurBcController', 'historiqueDevis');

// ===== DIRECTEUR IUT =====
$router->get('/directeur', 'EditeurBcController', 'dashboard');
$router->get('/directeur/dashboard', 'EditeurBcController', 'dashboard');
$router->get('/directeur/signer-devis', 'EditeurBcController', 'signerDevis');
$router->post('/directeur/signer-devis', 'EditeurBcController', 'signerDevis');
$router->get('/directeur/devis', 'EditeurBcController', 'devisASigner');
$router->get('/directeur/bons-commande', 'EditeurBcController', 'bonsCommande');
$router->get('/directeur/voir-devis', 'EditeurBcController', 'voirDevis');

// ===== ADMIN =====
$router->get('/admin', 'AdminController', 'dashboard');
$router->get('/admin/dashboard', 'AdminController', 'dashboard');
$router->post('/admin/test-mail', 'AdminController', 'testMail');
$router->get('/admin/utilisateurs', 'AdminController', 'utilisateurs');
$router->get('/admin/ajouter-utilisateur', 'AdminController', 'ajouterUtilisateur');
$router->post('/admin/ajouter-utilisateur', 'AdminController', 'ajouterUtilisateur');
$router->get('/admin/modifier-utilisateur', 'AdminController', 'modifierUtilisateur');
$router->post('/admin/update-utilisateur', 'AdminController', 'updateUtilisateur');
$router->post('/admin/supprimer-utilisateur', 'AdminController', 'supprimerUtilisateur');
$router->get('/admin/fournisseurs', 'AdminController', 'fournisseurs');
$router->get('/admin/ajouter-fournisseur', 'AdminController', 'ajouterFournisseur');
$router->post('/admin/ajouter-fournisseur', 'AdminController', 'ajouterFournisseur');
$router->get('/admin/modifier-fournisseur', 'AdminController', 'modifierFournisseur');
$router->post('/admin/update-fournisseur', 'AdminController', 'updateFournisseur');
$router->post('/admin/supprimer-fournisseur', 'AdminController', 'supprimerFournisseur');
$router->get('/admin/departements', 'AdminController', 'departements');
$router->get('/admin/ajouter-departement', 'AdminController', 'ajouterDepartement');
$router->post('/admin/ajouter-departement', 'AdminController', 'ajouterDepartement');
$router->get('/admin/modifier-departement', 'AdminController', 'modifierDepartement');
$router->post('/admin/update-departement', 'AdminController', 'updateDepartement');
$router->post('/admin/supprimer-departement', 'AdminController', 'supprimerDepartement');
$router->get('/admin/devis', 'AdminController', 'devis');
$router->get('/admin/commandes', 'AdminController', 'commandes');
$router->get('/admin/colis', 'AdminController', 'colis');
$router->get('/admin/console', 'AdminController', 'console');
$router->post('/admin/console/executer', 'AdminController', 'executerSql');

// ===== TICKETS / ASSISTANCE =====
$router->get('/tickets', 'TicketController', 'index');
$router->get('/tickets/nouveau', 'TicketController', 'nouveau');
$router->post('/tickets/creer', 'TicketController', 'creer');
$router->get('/tickets/:id', 'TicketController', 'detail');
$router->post('/tickets/:id/message', 'TicketController', 'repondre');
$router->post('/tickets/:id/statut', 'TicketController', 'changerStatut');

// ===== PRESENCE / UTILISATEURS CONNECTES =====
$router->get('/presence', 'PresenceController', 'index');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($currentUser && !in_array($uri, $publicRoutes)) {
        if (!AuthorizationMiddleware::check($uri, $currentUser)) {
            http_response_code(403);
            require_once __DIR__ . '/../lib-tools/pages/errors/403.php';
            exit;
        }
    }

    [$controllerClass, $methodName, $params] = $router->dispatch($method, $uri);

    if (is_callable($controllerClass)) {
        $controllerClass();
    } else {
        $controller = new $controllerClass();
        call_user_func_array([$controller, $methodName], $params);
    }

} catch (\Exception $e) {
    $code = $e->getCode();

    if ($code == 404) {
        http_response_code(404);
        require_once __DIR__ . '/../lib-tools/pages/errors/404.php';
    } elseif ($code == 403) {
        http_response_code(403);
        require_once __DIR__ . '/../lib-tools/pages/errors/403.php';
    } else {
        http_response_code(500);
        $errorMessage = $e->getMessage();
        $errorTrace = $e->getTraceAsString();
        require_once __DIR__ . '/../lib-tools/pages/errors/500.php';
    }
}
