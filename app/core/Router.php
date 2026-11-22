<?php

class Router
{
    private $routes = [];

    public function get($url, $controller, $method)
    {
        $this->routes['GET'][$url] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function post($url, $controller, $method)
    {
        $this->routes['POST'][$url] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function dispatch()
    {
        $url = $_GET['url'] ?? 'login';

        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if(isset($this->routes[$requestMethod][$url]))
        {
            $controllerName =
                $this->routes[$requestMethod][$url]['controller'];

            $methodName =
                $this->routes[$requestMethod][$url]['method'];

            $controller = new $controllerName();

            $controller->$methodName();
        }
        else
        {
            echo "<h1>404 Page Not Found</h1>";
        }
    }
}