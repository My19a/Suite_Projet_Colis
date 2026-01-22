<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../controllers/DepartementController.php';

$controller = new DepartementController();
$controller->mesColis();