<?php

namespace App\Application\Port\Inbound;

interface BookingServicePort {
    public function save($data, $id): bool;
}