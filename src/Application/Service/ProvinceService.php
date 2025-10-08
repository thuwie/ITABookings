<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Outbound\ProvinceRepositoryPort;
use App\Domain\Entity\Province;
use App\Domain\Entity\ProvinceImages;
use Illuminate\Support\Carbon;

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
        
       $addedEntity = $this->provinceRepositoryPort->save($newProvince);
       $imgs = $this->provinceRepositoryPort->saveProvinceImages($imgs, $addedEntity );

        $provinceId = $addedEntity["id"];
        $provinceImages = array_map(function($img) use ($provinceId) {
        return [
            'province_id' => $provinceId,
            'url'         => $img['url'],
            'publicUrl'   => $img['original_name'],
            'created_at'  =>Carbon::now(),
            'updated_at'  =>Carbon::now()
        ];
    }, $imgs);
       
       $result  = $this->provinceRepositoryPort->saveManyProvinceImages($provinceImages);

        return $result
        ? ['status' => 'success', 'message' => 'Province and images saved successfully']
        : ['status' => 'failed', 'message' => 'Province and images saved unsuccessfully'];
    }

   


}
