<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Outbound\ProvinceRepositoryPort;
use App\Application\Port\Outbound\TravelSpotRepositoryPort;
use  App\Application\Port\Outbound\FoodCourtRepositoryPort;
use App\Domain\Entity\Province;
use Illuminate\Support\Carbon;

class ProvinceService implements ProvinceServicePort {
    private ProvinceRepositoryPort $provinceRepositoryPort;
    private TravelSpotRepositoryPort $travelSportRepositoryPort;
    private FoodCourtRepositoryPort $foodCourtRepositoryPort;

    public function __construct(ProvinceRepositoryPort $provinceRepositoryPort,
    TravelSpotRepositoryPort $travelSportRepositoryPort,
    FoodCourtRepositoryPort $foodCourtRepositoryPort
    ) {
        $this->provinceRepositoryPort = $provinceRepositoryPort;
        $this->travelSportRepositoryPort = $travelSportRepositoryPort;
        $this->foodCourtRepositoryPort = $foodCourtRepositoryPort;
    }

    public function createProvince($province, array $imgs) {
        $newProvince = new Province(
            0,
            $province["code"],
            $province["name"],
            $province["type"],
            $province["description"],
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

    public function getProvinces():array {
        $result  = $this->provinceRepositoryPort->getProvinces();
        return $result;
    }

  public function getProvincesWithTravelSports(): array
  {
        $provinces  = $this->provinceRepositoryPort->getProvinces();
        $provinceIds = array_map(fn($p) => $p->getId(), $provinces);

        // Lấy travel spots
        $travelSpots = $this->travelSportRepositoryPort->getTravelSpotsByProvinceIds($provinceIds);

        // Lấy tất cả ảnh của các travel spots
        $travelSpotIds = array_map(fn($spot) => is_object($spot) ? $spot->id : $spot['id'], $travelSpots);

        $travelImgs = $this->travelSportRepositoryPort->getTravelSpotImagesByTravelSpotIds($travelSpotIds);

        // Nhóm ảnh theo id_travel_spot
        $imgsBySpot = [];
        foreach ($travelImgs as $img) {
            $id = $img->id_travel_spot;
            if (!isset($imgsBySpot[$id])) {
                $imgsBySpot[$id] = [];
            }
            $imgsBySpot[$id][] = $img;
        }

        $result = [];

        foreach ($provinces as $province) {
            $provinceId = (int)$province->getId();

            // Lọc các travelSpot thuộc province này
            $spotsForProvince = array_filter($travelSpots, function ($spot) use ($provinceId) {
                $spotProvinceId = is_object($spot) ? (int)$spot->province_id : (int)$spot['province_id'];
                return $spotProvinceId === $provinceId;
            });

            // Gán ảnh cho từng travel spot
            $spotsWithImgs = array_map(function ($spot) use ($imgsBySpot) {
                $spotId = is_object($spot) ? $spot->id : $spot['id'];
                $spot->images = $imgsBySpot[$spotId] ?? [];
                return $spot;
            }, array_values($spotsForProvince));

            $result[] = [
                'province'    => $province,
                'travelSpots' => $spotsWithImgs,
            ];
            }
            return $result;
    }

    
    public function getProvincesWithImages():array {
        $result = $this->provinceRepositoryPort->getProvincesWithImages();
        return $result;
    }

    public function getProvinceByIdWithImages($id) {
        $result = $this->provinceRepositoryPort->getProvinceByIdWithImages($id);
        return $result;
    }

    public function getFoodCourtsBelongTpProvince(): array
    {
        $provinces = $this->getProvinces();
        $result = [];

        foreach ($provinces as $province) {
            $result[] = [
                'province'    => $province->toArray(),
                'foodCourts'  => $this->foodCourtRepositoryPort
                    ->getFoodCourtsWithImagesByProvinceId($province->getId())
            ];
        }

        return $result;
    }

    
}
