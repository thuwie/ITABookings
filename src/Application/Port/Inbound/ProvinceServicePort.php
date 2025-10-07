<?php

namespace App\Application\Port\Inbound;

interface ProvinceServicePort {
   public function createProvince($province, array $imgs);
}
