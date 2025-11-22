<?php

namespace App\Application\Port\Inbound;

interface RouteServicePort {
     public function createRoute($route);
     public function findVehiclesByRoute($data): array;
}
