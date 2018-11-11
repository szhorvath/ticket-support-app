<?php

namespace App;

use App\Exceptions\MethodNotAllowedException;
use App\Exceptions\RouteNotFoundException;

class Router
{
    protected $currentPath;

    protected $routes = [];

    protected $methods = [];

    protected $currentMethod;

    public function setCurrentPath($currentPath = '/')
    {
        $this->currentPath = $currentPath;
    }


    public function setCurrentMethod($currentMethod)
    {
        $this->currentMethod = $currentMethod;
    }

    public function map($methods, $pattern, $handler)
    {
        if (!is_string($pattern)) {
            throw new \InvalidArgumentException('Route pattern must be a string');
        }

        return $this->addRoute($methods, $pattern, $handler);
    }

    public function addRoute(array $methods, string $pattern, $handler)
    {
        $this->routes[$pattern] = $handler;
        $this->methods[$pattern] = $methods;
    }

    public function getCurrentRoute()
    {
        if (!isset($this->routes[$this->currentPath])) {
            throw new RouteNotFoundException('No route registered for ' . $this->currentPath);
        }

        if (!in_array($this->currentMethod, $this->methods[$this->currentPath])) {
            throw new MethodNotAllowedException;
        }

        return $this->routes[$this->currentPath];
    }
}
