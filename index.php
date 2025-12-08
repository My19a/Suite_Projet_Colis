<?php
// Au début du fichier
session_start();
require_once 'src/Models/Database.php';
require_once 'src/Controllers/ServicePostalController.php';

// Récupère la route
$route = $_GET['route'] ?? 'home';

// Routeur simple
switch($route) {
    case 'service-postal':
        $controller = new ServicePostalController();
        $controller->index();
        break;
        
    case 'service-postal-transferer':
        $controller = new ServicePostalController();
        $controller->transferer();
        break;
        
    // ... tes autres routes existantes
    
    default:
        echo "Page d'accueil - en construction";
        break;
}
