<?php
/**
 * Router pour le serveur PHP intégré (php -S)
 * Simule le comportement de .htaccess avec mod_rewrite (cas d'utilisation avec apache mais pas notre cas à nous)
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . $uri;

if ($uri !== '/' && file_exists($filePath) && is_file($filePath)) {
    return false; // Le serveur PHP intégré va servir le fichier
}

// sinon on route et forcee vers index.php
require_once __DIR__ . '/index.php';
