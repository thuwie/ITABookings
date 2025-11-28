<?php

namespace App\Application\Port\Inbound;

interface BookingServicePort {
    public function save($data, $id): array;
    public function bookingConfirming(string $data): bool;
}