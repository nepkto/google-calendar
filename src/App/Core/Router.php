<?php

namespace App\Core;

class Router
{
    protected $routes = [];
    protected $callbackRoutes = [];

    public function addRoute($method, $path, $controller, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function addCallbackRoute($path, $controller, $action)
    {
        $this->callbackRoutes[] = [
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Check if the current path is a callback route
        foreach ($this->callbackRoutes as $route) {
            if (strpos($uri, $route['path']) === 0) {
                $this->executeController($route['controller'], $route['action']);
                return;
            }
        }

        // Check regular routes
        foreach ($this->routes as $route) {
            $params = [];
            if ($this->matchRoute($route['path'], $uri, $params) && $route['method'] === $method) {
                $this->executeController($route['controller'], $route['action'], $params);
                return;
            }
        }

        // If no route matches, show 404 error
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }

    protected function matchRoute($routePath, $uri, &$params)
    {
        // Remove trailing slashes for consistent matching
        $routePath = rtrim($routePath, '/');
        $uri = rtrim($uri, '/');

        // Convert route path to a regular expression
        $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $routePath);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Remove the full match
            $params = $matches;
            return true;
        }

        return false;
    }

    protected function executeController($controller, $action, $params = [])
    {
        if (!class_exists($controller)) {
            echo "Error: Controller class $controller does not exist.<br>";
            return;
        }

        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $action)) {
            echo "Error: Action $action does not exist in controller $controller.<br>";
            return;
        }

        call_user_func_array([$controllerInstance, $action], $params);
    }
}
