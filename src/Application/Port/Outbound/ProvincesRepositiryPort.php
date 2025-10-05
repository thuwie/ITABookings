<?php
namespace App\Application\Port\Outbound;

use App\Domain\Entity\Province;

interface ProvincesRepositoryPort {
    public function save(Province $province): void;
}
