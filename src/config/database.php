<?php

$config = require __DIR__ . '/app.php';
$db = $config['database'];

$dsn = sprintf(
    "mysql:host=%s;port=%s;dbname=%s;charset=%s",
    $db['host'],
    $db['port'],
    $db['name'],
    $db['charset'] ?? 'utf8mb4'
);

$user = $db['user'];
$password = $db['password'];
