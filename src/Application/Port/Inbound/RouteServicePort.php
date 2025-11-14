<?php

namespace App\Application\Port\Inbound;

interface RouteServicePort {
     public function createRoute($route);
     public function findRoutes(string $from, string $to);
}
