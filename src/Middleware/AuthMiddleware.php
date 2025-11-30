<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Khởi động session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra user login
        $user = $_SESSION['user'] ?? null;

        // Chưa login → redirect về login và dừng chain middleware
        if (!$user) {
            $redirectUrl = '/login?redirect=' . urlencode($request->getUri()->getPath());
            $_SESSION['redirect_after_login'] = $request->getUri()->getPath();
            return (new Response())
                ->withHeader('Location', $redirectUrl)
                ->withStatus(302);
        }

        // Đã login → lưu redirect path dựa trên role
        $role = $user['role'] ?? 'user';

        if ($role === 'admin') {
            $_SESSION['redirect_after_login'] = '/admin/dashboard';
        } else {
            $uri = $request->getUri()->getPath();
            $_SESSION['redirect_after_login'] = $uri;
        }

        // Tiếp tục middleware tiếp theo
        return $handler->handle($request);
    }
}