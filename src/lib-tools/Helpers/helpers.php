<?php

/**
 * Fonctions helper globales
 */

function url(string $path): string
{
    $config = require __DIR__ . '/../../config/app.php';
    $base = rtrim($config['base_url'], '/');
    return $base . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function view(string $viewName, array $data = []): void
{
    extract($data);
    $viewPath = __DIR__ . "/../../public/views/{$viewName}.php";

    if (!file_exists($viewPath)) {
        throw new \Exception("View not found: {$viewName}");
    }

    require $viewPath;
}

function asset(string $path): string
{
    $config = require __DIR__ . '/../../config/app.php';
    return rtrim($config['base_url'], '/') . '/assets/' . ltrim($path, '/');
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function currentUser(): ?User
{
    return $_SESSION['user'] ?? null;
}

function isAuthenticated(): bool
{
    return isset($_SESSION['user']) && $_SESSION['user'] instanceof User;
}

function hasRole(string $role): bool
{
    $user = currentUser();
    return $user !== null && $user->hasRole($role);
}

function config(string $key = null): mixed
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/../../config/app.php';
    }

    if ($key === null) return $config;

    $keys = explode('.', $key);
    $value = $config;
    foreach ($keys as $k) {
        if (!isset($value[$k])) return null;
        $value = $value[$k];
    }
    return $value;
}

function ticketNotifsCount(): int
{
    if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof User)) return 0;
    try {
        require_once __DIR__ . '/../../public/models/Model.php';
        $db = Model::getModel()->bd;
        $req = $db->prepare("SELECT COUNT(*) FROM notification WHERE id_utilisateur = ? AND lu = 0");
        $req->execute([$_SESSION['user']->getId()]);
        return (int) $req->fetchColumn();
    } catch (\Throwable $e) { return 0; }
}
