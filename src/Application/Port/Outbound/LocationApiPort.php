<?php
namespace App\Application\Port\Outbound;

interface LocationApiPort {
    public function getProvinces(): array;
    public function getWardsByProvince(int $provinceCode): array;
}
