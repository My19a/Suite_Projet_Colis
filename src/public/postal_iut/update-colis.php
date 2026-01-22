<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../controllers/PostalIutController.php';

$controller = new PostalIutController();
$controller->updateColis();
