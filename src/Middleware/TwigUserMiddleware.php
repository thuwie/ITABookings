<?php

namespace App\Middleware;

class TwigUserMiddleware
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function __invoke($request, $handler)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Lấy user từ session nếu có
        $authUser = $_SESSION['user'] ?? null;

        // Đưa vào Twig
        $this->twig->addGlobal('authUser', $authUser);

        return $handler->handle($request);
    }   
}
