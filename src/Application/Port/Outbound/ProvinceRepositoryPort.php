<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Province;

interface ProvinceRepositoryPort {
    public function save(Province $province): array;
    public function saveProvinceImages(array $imgs): array;
}
