<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Booking;

interface BookingRepositoryPort {
    public function save(Booking $booking): array;
}
