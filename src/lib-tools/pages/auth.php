<?php

/**
 * Fichier d'authentification - À inclure en haut de chaque page protégée
 */

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../Auth/User.php';
require_once __DIR__ . '/../Auth/AuthorizationMiddleware.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof User)) {
    header('Location: /dev-login');
    exit;
}

$currentUser = $_SESSION['user'];

// Déterminer la route actuelle pour vérifier les permissions
$currentRoute = $_SERVER['REQUEST_URI'];
$currentRoute = parse_url($currentRoute, PHP_URL_PATH);
$currentRoute = ltrim($currentRoute, '/');

// Extraire le module (admin, finance, postal_iut, etc.)
$parts = explode('/', $currentRoute);
$module = $parts[0] ?? '';

// Vérifier les permissions
if (!empty($module) && $module !== 'dev-login' && $module !== 'logout') {
    if (!AuthorizationMiddleware::check($currentRoute, $currentUser)) {
        http_response_code(403);
        require_once __DIR__ . '/errors/403.php';
        exit;
    }
}
