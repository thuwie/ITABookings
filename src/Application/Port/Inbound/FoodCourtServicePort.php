<?php

namespace App\Application\Port\Inbound;

interface FoodCourtServicePort {
   public function createFoodCourt($foodCourt, array $imgs);
   public function getFoodCourtsWithImages();
   public function getFoodCourtsWithImagesByProvinceId($idProvince):array;
   public function getFoodCourtsWithImagesByTravelSpotId($idTravelSpot):array;
   public function getFoodCourtById($id);
}
