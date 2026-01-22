<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../controllers/DepartementController.php';

$controller = new DepartementController();

// 🔴 SI FORMULAIRE ENVOYÉ
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller->envoyerDevis();
    exit;
}

// 🟢 SINON → AFFICHER LE FORMULAIRE
$controller->creerDevis();