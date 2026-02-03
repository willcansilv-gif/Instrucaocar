<?php
declare(strict_types=1);

namespace App\Helpers;

class Router
{
    private array $routes = [];

    public function get(string $path, string $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $handler = $this->routes[$method][$path] ?? null;
        if (!$handler) {
            http_response_code(404);
            echo 'Página não encontrada';
            return;
        }
        [$controllerName, $action] = explode('@', $handler);
        $class = 'App\\Controllers\\' . $controllerName;
        if (!class_exists($class)) {
            http_response_code(500);
            echo 'Controlador inválido';
            return;
        }
        $controller = new $class();
        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo 'Ação inválida';
            return;
        }
        $controller->$action();
    }
}
