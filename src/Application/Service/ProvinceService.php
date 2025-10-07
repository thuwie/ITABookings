<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Outbound\ProvinceRepositoryPort;
use App\Domain\Entity\Province;

class ProvinceService implements ProvinceServicePort {
       private ProvinceRepositoryPort $provinceRepositoryPort;

    public function __construct(ProvinceRepositoryPort $provinceRepositoryPort) {
        $this->provinceRepositoryPort = $provinceRepositoryPort;
    }

    public function createProvince($province, array $imgs) {
        $newProvince = new Province(
            0,
            $province["code"],
            $province["name"],
            $province["type"],
        );
        
       $entity["id"] = $this->provinceRepositoryPort->save($newProvince);
       $imgs = $this->provinceRepositoryPort->saveProvinceImages($imgs);
       return $imgs;
    }

   


}
