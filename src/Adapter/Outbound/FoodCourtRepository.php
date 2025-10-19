<?php
namespace App\Adapter\Outbound;

use Illuminate\Database\Capsule\Manager as DB;
use App\Application\Port\Outbound\FoodCourtRepositoryPort;
use App\Domain\Entity\FoodCourt;
use App\Helper\FileHelper;
use Illuminate\Support\Carbon;

class FoodCourtRepository implements FoodCourtRepositoryPort {

    public function save(FoodCourt $foodCourt): array
    {
        // Thêm record vào bảng food_courts
        $id = DB::table('food_courts')->insertGetId([
            'name'         => $foodCourt->getName(),
            'description'  => $foodCourt->getDescription(),
            'address'      => $foodCourt->getAddress(),
            'province_id'  => $foodCourt->getProvinceId(),
            'travel_spot_id'=> $foodCourt->getTravelSpotId(),
            'open_time'    => $foodCourt->getOpenTime(),
            'close_time'   => $foodCourt->getCloseTime(),
            'average_star' => $foodCourt->getAverageStar(),
            'total_rates'  => $foodCourt->getTotalRates(),
            'price_from'   => $foodCourt->getPriceFrom(),
            'price_to'     => $foodCourt->getPriceTo(),
            'created_at'   => $foodCourt->getCreatedAt() ?? Carbon::now(),
            'updated_at'   => $foodCourt->getUpdatedAt() ?? Carbon::now(),
        ]);

        $foodCourtArray = $foodCourt->toArray();
        $foodCourtArray['id'] = $id;

        return $foodCourtArray;
    }

    //Hàm này mới chỉ để lưu ảnh vào folder, nơi cất giữ ảnh thật
    public function saveFoodCourtImages(array $imgs, $newFoodCourt): array
    {
        $savedFiles = [];
        $folderName = FileHelper::sanitizeFolderName($newFoodCourt['name']);
        
        // Đặt thư mục upload tương đối (trong src/uploads/provinces)
        $uploadDir = __DIR__ . "/../../../uploads/food-court/{$folderName}/";

        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }


        foreach ($imgs as $img) {
            // Đảm bảo chỉ xử lý file hợp lệ
            if ($img->getError() === UPLOAD_ERR_OK) {
                $originalName = $img->getClientFilename();

                // Tạo tên file an toàn + duy nhất
                $safeName = uniqid('food-court_', true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

                // Đường dẫn đầy đủ
                $filePath = $uploadDir . $safeName;

                // Di chuyển file tạm đến thư mục upload
                $img->moveTo($filePath);

                // Lưu lại thông tin file
                $savedFiles[] = [
                    'original_name' => $originalName,
                    'file_name'     => $safeName,
                    'file_path'     => $filePath,
                    'url'           => "/uploads/food-court/{$folderName}/" . $safeName,
                ];
            }
        }

        return $savedFiles;
    }

    //Hàm này sẽ lưu các url đã lưu ảnh ở đâu folder nào xuống DB
    public function saveManyFoodCourtImages(array $imgs): bool {
     return DB::table('food_court_images')->insert($imgs);
    }

}
