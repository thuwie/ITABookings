<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Province;

interface ProvinceRepositoryPort {
    public function getProvinces(): array;
    public function save(Province $province): array;
    public function saveProvinceImages(array $imgs, $newProvince ): array;
    public function saveManyProvinceImages(array $imgs): bool;
    public function findById(int $id): ?array;
    public function getProvincesWithImages():array;
    public function getProvinceByIdWithImages($id);
}
