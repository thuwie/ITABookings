<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Driver;

interface DriverRepositoryPort {
    public function save(Driver $driver): Driver;
    public function findUnVerifiedAccountByUserId(int $userId): ?Driver;
}
