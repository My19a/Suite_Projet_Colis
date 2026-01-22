<?php

class Router
{
    private array $routes = [];

    public function get(string $pattern, $controller, ?string $method = null): void
    {
        $this->routes['GET'][$pattern] = is_callable($controller)
            ? $controller
            : [$controller, $method];
    }

    public function post(string $pattern, $controller, ?string $method = null): void
    {
        $this->routes['POST'][$pattern] = is_callable($controller)
            ? $controller
            : [$controller, $method];
    }

    public function dispatch(string $method, string $uri): array
    {
        if (!isset($this->routes[$method])) {
            throw new \Exception("Method {$method} not supported", 405);
        }

        $uri = rtrim($uri, '/');
        if ($uri === '') $uri = '/';

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            if (is_callable($handler)) {
                return [$handler, null, []];
            }
            [$controller, $methodName] = $handler;
            return [$controller, $methodName, []];
        }

        foreach ($this->routes[$method] as $pattern => $handler) {
            $params = $this->matchPattern($pattern, $uri);
            if ($params !== false) {
                if (is_callable($handler)) {
                    return [$handler, null, $params];
                }
                [$controller, $methodName] = $handler;
                return [$controller, $methodName, $params];
            }
        }

        throw new \Exception("Route not found: {$method} {$uri}", 404);
    }

    private function matchPattern(string $pattern, string $uri): array|false
    {
        $regex = preg_replace_callback('/:([a-z]+)/', function ($matches) {
            return $matches[1] === 'id' ? '([0-9]+)' : '([a-zA-Z0-9\-_]+)';
        }, $pattern);

        $regex = str_replace('*', '(.*)', $regex);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches)) {
            array_shift($matches);
            return $matches;
        }

        return false;
    }

    public function getRoutes(): array { return $this->routes; }
}
