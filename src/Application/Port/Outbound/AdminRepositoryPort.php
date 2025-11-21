<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Provider;

interface AdminRepositoryPort {
    public function approveProvider(int $providerId);
}
