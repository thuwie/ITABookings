<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Route;

interface RouteRepositoryPort {
     public function save(Route $route): array;
     public function getRoute(string $from, string $to): ?array;
}
