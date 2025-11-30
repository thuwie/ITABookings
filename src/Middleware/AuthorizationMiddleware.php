<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Illuminate\Database\Capsule\Manager as DB;


class AuthorizationMiddleware implements MiddlewareInterface
{
    private int $requiredRole;

    public function __construct(int $requiredRole)
    {
        $this->requiredRole = $requiredRole;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = null;

        if(isset( $_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
        };

        // Lấy role của user từ DB
        $userRoles = DB::table('user_roles')
            ->where('user_roles.user_id', $userId)
            ->where('role_id', $this->requiredRole)
            ->exists();

        if($userRoles) {
            return $handler->handle($request);
        }

        // Không có quyền
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write("Bạn không có quyền truy cập vào đường link này");
        return $response->withStatus(403);
    }
}
