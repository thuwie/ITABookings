<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Route;

interface RouteRepositoryPort {
     public function save(Route $route): array;
}
