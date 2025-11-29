<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $role = $_SESSION['user']['role'];

            if($role === 'admin') {
                $_SESSION['redirect_after_login'] = '/admin/dashboard';
            } else {
                     // Get the URL user is trying to access
                 $_SESSION['redirect_after_login'] = (string)$request->getUri();
            };
            // Redirect to login with redirect query parameter
            $response = new \Slim\Psr7\Response();
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }

        return $handler->handle($request);
    }

}
