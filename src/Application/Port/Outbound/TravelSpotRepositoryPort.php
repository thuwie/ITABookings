<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\TravelSpot;

interface TravelSpotRepositoryPort {
     public function save(TravelSpot $travelSpot): array;
     public function saveTravelSpotImages(array $imgs, $newTravelSpot): array;
     public function saveManyTravelSpotImages(array $imgs): bool;
     public function getTravelSpotsByProvinceIds(array $provinceIds): array;
     public function getTravelSpotImagesByTravelSpotIds(array $travelSpotIds): array;
     public function getTravelSpots(): array;
     public function getTravelSpotsWithImages():array;
     public function getTravelSpotsWithImagesByProvinceId($idProvince):array;
}
