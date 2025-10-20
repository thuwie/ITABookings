<?php

namespace App\Application\Port\Inbound;
use App\Domain\Entity\Province;

interface ProvinceServicePort {
   public function createProvince($province, array $imgs);
   public function getProvinces():array;
   public function getProvincesWithTravelSports():array;
   public function getProvincesWithImages():array;
   public function getProvinceByIdWithImages($id);
}
