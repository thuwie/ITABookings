<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\ExtraCost;

interface AdminRepositoryPort {
    public function approveProvider(int $providerId);
    public function saveExtraCosts(ExtraCost $extraCosts): bool;
    public function getExtraCost(): ?ExtraCost;
}
