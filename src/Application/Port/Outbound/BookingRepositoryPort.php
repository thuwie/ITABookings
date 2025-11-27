<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Booking;

interface BookingRepositoryPort {
    public function save(Booking $booking): array;
    public function findById(int $id): ?Booking;
    public function updateById(int $id, Booking $data): array;
}
