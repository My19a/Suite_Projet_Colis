<?php

/**
 * Bootstrap de l'application
 * Ce fichier initialise l'autoloader et les composants de base
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Charger le .env avec phpdotenv (createUnsafeImmutable pour que getenv() fonctionne)
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$config = require __DIR__ . '/../config/app.php';

if ($config['env'] === 'development') {
    error_reporting(E_ALL);
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

return $config;
