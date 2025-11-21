<?php

namespace App\Application\Port\Inbound;

interface AdminServicePort {
    public function approveProvider($id): bool;
}