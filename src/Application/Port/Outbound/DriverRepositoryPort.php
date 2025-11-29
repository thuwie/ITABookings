<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Driver;

interface DriverRepositoryPort {
    public function save(Driver $driver): Driver;
    public function findByUserIdWithVerifyFallback(int $userId): ?Driver;
}
