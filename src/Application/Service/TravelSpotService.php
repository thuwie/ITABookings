<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\TravelSpotPort;
use App\Application\Port\Outbound\TravelSpotRepositoryPort;
use App\Domain\Entity\TravelSpot;
use Illuminate\Support\Carbon;

class TravelSpotService implements TravelSpotPort {
    private TravelSpotRepositoryPort $travelSpotRepositoryPort;

    public function __construct(TravelSpotRepositoryPort $travelSpotRepositoryPort) {
        $this->travelSpotRepositoryPort = $travelSpotRepositoryPort;
    }

    public function createTravelSpot($travelSpot, array $imgs) {

    //   $newTravelSpot = new TravelSpot(
    //     0,
    //     $travelSpot['name'],
    //     $travelSpot['description'],
    //     $travelSpot['province_id'],
    //   );

    //     return $newTravelSpot;
    //    $addedEntity = $this->travelSpotRepositoryPort->save($newProvince);
    //    $imgs = $this->provinceRepositoryPort->saveProvinceImages($imgs, $addedEntity );

    //     $provinceId = $addedEntity["id"];
    //     $provinceImages = array_map(function($img) use ($provinceId) {
    //     return [
    //         'province_id' => $provinceId,
    //         'url'         => $img['url'],
    //         'publicUrl'   => $img['original_name'],
    //         'created_at'  =>Carbon::now(),
    //         'updated_at'  =>Carbon::now()
    //     ];
    // }, $imgs);
       
    //    $result  = $this->provinceRepositoryPort->saveManyProvinceImages($provinceImages);

    //     return $result
    //     ? ['status' => 'success', 'message' => 'Province and images saved successfully']
    //     : ['status' => 'failed', 'message' => 'Province and images saved unsuccessfully'];
     }


}
