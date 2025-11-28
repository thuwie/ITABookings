<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\TravelSpotPort;
use App\Application\Port\Outbound\TravelSpotRepositoryPort;
use App\Adapter\Outbound\ProvinceRepository;
use App\Domain\Entity\TravelSpot;
use Illuminate\Support\Carbon;

class TravelSpotService implements TravelSpotPort {
    private TravelSpotRepositoryPort $travelSpotRepositoryPort;

    public function __construct(TravelSpotRepositoryPort $travelSpotRepositoryPort) {
        $this->travelSpotRepositoryPort = $travelSpotRepositoryPort;
    }

    public function createTravelSpot($travelSpot, array $imgs) {
        $repo = new ProvinceRepository();
        $province = $repo->findById($travelSpot['province_id']);


        $newTravelSpot = new TravelSpot(
            0,
            $travelSpot['name'] . ' - ' . $province['name'],
            $travelSpot['description'],
            $travelSpot['province_id'],
            $travelSpot['startTime'],
            $travelSpot['endTime'],
            0,
            $travelSpot['priceFrom'],
            $travelSpot['priceTo'],
            0,
            $travelSpot['fullAddress'] . ', ' . $province['type'] . ' ' . $province['name'],
        );

        $addedEntity = $this->travelSpotRepositoryPort->save($newTravelSpot);

        $imgs = $this->travelSpotRepositoryPort->saveTravelSpotImages($imgs, $addedEntity );

            $travelSpotId = $addedEntity["id"];
            $provinceImages = array_map(function($img) use ($travelSpotId) {
            return [
                'id_travel_spot' => $travelSpotId,
                'url'         => $img['url'],
                'publicUrl'   => $img['original_name'],
                'created_at'  =>Carbon::now(),
                'updated_at'  =>Carbon::now()
            ];
        }, $imgs);
        
        $result  = $this->travelSpotRepositoryPort->saveManyTravelSpotImages($provinceImages);

            return $result
            ? ['status' => 'success', 'message' => 'Travel spot and images saved successfully']
            : ['status' => 'failed', 'message' => 'Travel spot and images saved unsuccessfully'];
     }

    public function getTravelSpots():array {
        $result  = $this->travelSpotRepositoryPort->getTravelSpots();
        return $result;
    }

    public function getTravelSpotsWithImages():array {
        $result = $this->travelSpotRepositoryPort->getTravelSpotsWithImages();
        return $result;
    }

    public function getTravelSpotsWithImagesByProvinceId($idProvince):array {
      $result = $this->travelSpotRepositoryPort->getTravelSpotsWithImagesByProvinceId($idProvince);
      return $result;
    }

    public function getById($idTravelSPot) {
      $result = $this->travelSpotRepositoryPort->getById($idTravelSPot);
      return $result;
    }

    public function getProvinceByTravelSpotId($idTravelSPot) {
        $travelSpot = $this->getById($idTravelSPot);
        $travelSpotName = $travelSpot['name'];
        $province = $this->travelSpotRepositoryPort->getProvinceByTravelSpotId($idTravelSPot);
        $result = ['province' => $province, 'travelSpot' => $travelSpotName];
        return $result;
    }   
}

