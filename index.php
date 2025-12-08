<?php
// Au début du fichier
session_start();
require_once 'Backend/src/Models/Database.php';
require_once 'Backend/src/Controllers/ServicePostalController.php';

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
        
    
    
    default:
        echo "Page d'accueil - en construction";
        break;
}
