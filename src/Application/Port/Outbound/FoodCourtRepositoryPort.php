<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\FoodCourt;

interface FoodCourtRepositoryPort {
    public function save(FoodCourt $foodCourt): array;
    public function saveManyFoodCourtImages(array $imgs): bool;
    public function saveFoodCourtImages(array $imgs, $newFoodCourt): array;
}
