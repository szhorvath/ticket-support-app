<?php

namespace App\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Services\Auth\Auth;
use App\Middleware\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth;
    }

    public function handle(Request $request, Response $response)
    {
        if (!$this->auth->check()) {
            return $response->withJson('Unauthorized')->withStatus(401);
        }
    }
}
