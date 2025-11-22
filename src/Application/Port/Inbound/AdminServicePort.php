<?php

namespace App\Application\Port\Inbound;

interface AdminServicePort {
    public function approveProvider($id): bool;
    public function saveExtraCosts($data): bool;
    public function getExtraCost();
}