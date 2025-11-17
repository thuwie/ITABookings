<?php

namespace App\Application\Port\Inbound;


interface DriverServicePort {
    public function save($driver): bool;
}