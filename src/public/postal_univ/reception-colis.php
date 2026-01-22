<?php
require_once __DIR__ . '/../auth.php';

require_once __DIR__ . '/../controllers/PostalUnivController.php';
$controller = new PostalUnivController();
$controller->receptionColis();


