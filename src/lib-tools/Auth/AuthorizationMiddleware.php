<?php

class AuthorizationMiddleware
{
    public static function check(string $route, User $user): bool
    {
        $permissions = require __DIR__ . '/../../config/permissions.php';
        $userPermissions = $permissions[$user->getRole()] ?? [];
        $route = ltrim($route, '/');

        foreach ($userPermissions as $pattern) {
            if (self::matchRoute($route, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private static function matchRoute(string $route, string $pattern): bool
    {
        $regex = str_replace(['*', '/'], ['.*', '\\/'], $pattern);
        $regex = '#^' . $regex . '$#';
        return preg_match($regex, $route) === 1;
    }

    public static function authorize(string $route, User $user): void
    {
        if (!self::check($route, $user)) {
            http_response_code(403);
            die("Accès refusé.");
        }
    }
}
