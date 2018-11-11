<?php

namespace App;

use App\Router;
use App\Container;
use App\Http\Request;
use App\Http\Response;
use App\Exceptions\RouteNotFoundException;

class App
{
    protected $container;

    protected $middlewares = [];

    public function __construct()
    {
        $this->container = new Container([
            'router' => function () {
                return new Router;
            },
            'request' => function () {
                return new Request($_SERVER);
            },
            'response' => function () {
                return new Response;
            },
        ]);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function get($pattern, $handler)
    {
        $this->container->router->map(['GET'], $pattern, $handler);
    }

    public function post($pattern, $handler)
    {
        $this->container->router->map(['POST'], $pattern, $handler);
    }

    public function addMiddleware(string $name, $instance)
    {
        $this->middlewares[$name] = $instance;
    }


    public function use(array $names, callable $callable)
    {
        foreach ($names as $key => $name) {
            $response = $this->middlewares[$name]->handle($this->container->request, $this->container->response);

            if ($response instanceof Response) {
                $this->respond($response);
                exit;
            }
        }

        call_user_func($callable);
    }

    public function run()
    {
        $router = $this->container->router;
        $request = $this->container->request;

        $router->setCurrentPath($request->getPath());
        $router->setCurrentMethod($request->getMethod());

        try {
            $callable = $router->getCurrentRoute();
        } catch (RouteNotFoundException $e) {
            if ($this->container->has('errorHandler')) {
                $callable = $this->container->errorHandler;
            } else {
                return;
            }
        }

        return $this->respond($this->process($callable, $request, $this->container->response));
    }

    protected function process($callable, Request $request, Response $response)
    {
        if (is_callable($callable)) {
            call_user_func_array($callable, $params);
        } elseif (is_string($callable) && stripos($callable, '@') !== false) {
            list($controller, $method) = explode('@', $callable);
            return call_user_func_array([new $controller($this->container), $method], [$request, $response]);
        } else {
            throw new \InvalidArgumentException('Route second argument must be a string eg: calss@method or a closure');
        }
    }

    protected function respond($response)
    {
        if (!$response instanceof Response) {
            echo $response;
            return;
        }

        header(sprintf(
            'HTTP/%s %s %s',
            '1.1',
            $response->getStatusCode(),
            ''
        ));

        foreach ($response->getHeaders() as $header) {
            header($header[0] . ': ' . $header[1]);
        }

        echo $response->getBody();
    }
}
