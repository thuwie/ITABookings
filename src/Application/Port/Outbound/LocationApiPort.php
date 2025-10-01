<?php
namespace App\Application\Port\Outbound;

interface LocationApiPort {
    public function getProvinces(): array;
    // public function getDistricts(int $provinceId): array;
}
