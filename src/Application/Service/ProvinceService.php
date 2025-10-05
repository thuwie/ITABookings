<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Outbound\ProvincesRepositoryPort;

class ProvinceService implements ProvinceServicePort {
       private ProvincesRepositoryPort $provinceRepositoryPort;

    public function __construct(ProvincesRepositoryPort $provinceRepositoryPort) {
        $this->provinceRepositoryPort = $provinceRepositoryPort;
    }

    public function createProvince($province, $imgs):array {
        //valid use
        $test = $province;
     return [$test];
    }
}
