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
        // BẮT BUỘC: phải start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra session user
        if (!isset($_SESSION['user'])) {
            // Chưa đăng nhập → redirect
            $response = new \Slim\Psr7\Response();
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }

        // Đã đăng nhập → cho vào route
        return $handler->handle($request);
    }
}
