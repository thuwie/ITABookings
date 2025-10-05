<?php

namespace App\Application\Port\Inbound;

interface ProvinceServicePort {
    public function createProvince($province, $imgs): array;
}
