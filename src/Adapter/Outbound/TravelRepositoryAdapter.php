<?php
namespace App\Adapter\Outbound;

use Illuminate\Database\Capsule\Manager as DB;
use App\Application\Port\Outbound\TravelSpotRepositoryPort;
use App\Domain\Entity\TravelSpot;
use App\Helper\FileHelper;
use Illuminate\Support\Carbon;

class TravelRepositoryAdapter implements TravelSpotRepositoryPort {
    // public function getProvinces():array {
    // $results = DB::table('provinces')->get(); // Lấy toàn bộ dữ liệu
    //     $provinces = [];
    //     foreach ($results as $row) {
    //         $province = new Province(
    //             id:          $row->id,
    //             code:        $row->code,
    //             name:        $row->name,
    //             type:        $row->type,
    //             description: $row->description,
    //            createdAt: $row->created_at ? new \DateTimeImmutable($row->created_at) : null,
    //            updatedAt: $row->updated_at ? new \DateTimeImmutable($row->updated_at) : null
    //         );

    //         $provinces[] = $province;
    //     }

    //     return $provinces;
    // }
   public function save(TravelSpot $travelSpot): array
{
    $id = DB::table('travel_spots')->insertGetId([
        'name'         => $travelSpot->getName(),
        'description'  => $travelSpot->getDescription(),
        'province_id'  => $travelSpot->getProvinceId(),
        'open_time'    => $travelSpot->getOpenTime(),
        'close_time'   => $travelSpot->getCloseTime(),
        'average_rate' => $travelSpot->getAverageRate(),
        'price_from'   => $travelSpot->getPriceFrom(),
        'price_to'     => $travelSpot->getPriceTo(),
        'total_rates'  => $travelSpot->getTotalRates(),
        'full_address' => $travelSpot->getFullAddress(),
        'created_at'   => $travelSpot->getCreatedAt() ?? Carbon::now(),
        'updated_at'   => $travelSpot->getUpdatedAt() ?? Carbon::now(),
    ]);

    $travelSpotArray = $travelSpot->toArray();
    $travelSpotArray['id'] = $id;

    return $travelSpotArray;
}


    //Hàm này mới chỉ để lưu ảnh vào folder, nơi cất giữ ảnh thật
    public function saveProvinceImages(array $imgs, $newProvince): array
    {
        $savedFiles = [];
        $folderName = FileHelper::sanitizeFolderName($newProvince['name']);
        
        // Đặt thư mục upload tương đối (trong src/uploads/provinces)
        $uploadDir = __DIR__ . "/../../../uploads/provinces/{$folderName}/";

        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }


        foreach ($imgs as $img) {
            // Đảm bảo chỉ xử lý file hợp lệ
            if ($img->getError() === UPLOAD_ERR_OK) {
                $originalName = $img->getClientFilename();

                // Tạo tên file an toàn + duy nhất
                $safeName = uniqid('province_', true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

                // Đường dẫn đầy đủ
                $filePath = $uploadDir . $safeName;

                // Di chuyển file tạm đến thư mục upload
                $img->moveTo($filePath);

                // Lưu lại thông tin file
                $savedFiles[] = [
                    'original_name' => $originalName,
                    'file_name'     => $safeName,
                    'file_path'     => $filePath,
                    'url'           => "/uploads/provinces/{$folderName}/" . $safeName,
                ];
            }
        }

        return $savedFiles;
    }

    //Hàm này sẽ lưu các url đã lưu ảnh ở đâu folder nào xuống DB
    public function saveManyProvinceImages(array $imgs): bool {
     return DB::table('province_images')->insert($imgs);
}
}
