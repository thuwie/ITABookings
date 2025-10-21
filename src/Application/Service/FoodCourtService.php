<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\FoodCourtServicePort;
use App\Application\Port\Outbound\FoodCourtRepositoryPort;
use App\Adapter\Outbound\FoodCourtRepository;
use App\Domain\Entity\FoodCourt;
use Illuminate\Support\Carbon;

class FoodCourtService implements FoodCourtServicePort {
    private FoodCourtRepositoryPort $foodCourtRepositoryPort;

    public function __construct(FoodCourtRepositoryPort $foodCourtRepositoryPort) {
        $this->foodCourtRepositoryPort = $foodCourtRepositoryPort;
    }

      public function createFoodCourt($foodCourt, array $imgs) {

        
      $newFoodCourt = new FoodCourt(
        0,
        $foodCourt['name'],
        $foodCourt['description'],
        $foodCourt['address'],
        $foodCourt['province_id'],
        $foodCourt['travel_spot_id'],
        $foodCourt['open_time'],
        $foodCourt['close_time'],
        0.0, // average_star mặc định
        0,   // total_rates mặc định
        $foodCourt['price_from'],
        $foodCourt['price_to'],
        new \DateTimeImmutable(),
        new \DateTimeImmutable()
    );

        $addedEntity = $this->foodCourtRepositoryPort->save($newFoodCourt);

        $imgs = $this->foodCourtRepositoryPort->saveFoodCourtImages($imgs, $addedEntity);

            $foodCourtId = $addedEntity["id"];
            $foodCourtImages = array_map(function($img) use ($foodCourtId) {
            return [
                  'food_court_id' => $foodCourtId,        // đúng với DB (2 dấu gạch dưới)
                  'url'           => $img['url'],          // đường dẫn file
                  'public_url'    => $img['original_name'], // tên file gốc hoặc public link
                  'created_at'    => Carbon::now(),
                  'updated_at'    => Carbon::now()
            ];
        }, $imgs);
        
        $result  = $this->foodCourtRepositoryPort->saveManyFoodCourtImages($foodCourtImages);

            return $result
            ? ['status' => 'success', 'message' => 'Food court and images saved successfully']
            : ['status' => 'failed', 'message' => 'Food court and images saved unsuccessfully'];
      }

      public function getFoodCourtsWithImages():array {
        $result = $this->foodCourtRepositoryPort->getFoodCourtsWithImages();
        return $result;
      }

      public function getFoodCourtsWithImagesByProvinceId($provinceId):array {
        $result = $this->foodCourtRepositoryPort->getFoodCourtsWithImagesByProvinceId($provinceId);
        return $result;
      }
}

