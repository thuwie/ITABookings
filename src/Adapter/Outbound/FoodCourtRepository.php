<?php
namespace App\Adapter\Outbound;

use Illuminate\Database\Capsule\Manager as DB;
use App\Application\Port\Outbound\FoodCourtRepositoryPort;
use App\Domain\Entity\FoodCourt;
use App\Helper\FileHelper;
use Illuminate\Support\Carbon;
use App\Domain\Entity\FoodCourtImage;
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

    public function getFoodCourtsWithImages():array {
        $results = DB::table('food_courts')
        ->leftJoin('food_court_images', 'food_courts.id', '=', 'food_court_images.food_court_id')
        ->select(
            'food_courts.id as food_court_id',
            'food_courts.name',
            'food_courts.description',
            'food_courts.address',
            'food_courts.province_id',
            'food_courts.travel_spot_id',
            'food_courts.open_time',
            'food_courts.close_time',
            'food_courts.average_star',
            'food_courts.total_rates',
            'food_courts.price_from',
            'food_courts.price_to',
            'food_courts.created_at',
            'food_courts.updated_at',
            'food_court_images.id as image_id',
            'food_court_images.url as image_url',
            'food_court_images.public_url as image_public_url',
            'food_court_images.created_at as image_created_at',
            'food_court_images.updated_at as image_updated_at'
        )
        ->get();

        $foodCourts = [];

        foreach ($results as $row) {
            $fcId = $row->food_court_id;

            // Nếu chưa khởi tạo FoodCourt object thì tạo mới
            if (!isset($foodCourts[$fcId])) {
                $foodCourt = new FoodCourt(
                    id: $fcId,
                    name: $row->name,
                    description: $row->description,
                    address: $row->address,
                    provinceId: $row->province_id,
                    travelSpotId: $row->travel_spot_id,
                    openTime: $row->open_time,
                    closeTime: $row->close_time,
                    averageStar: $row->average_star,
                    totalRates: $row->total_rates,
                    priceFrom: $row->price_from,
                    priceTo: $row->price_to,
                    createdAt: $row->created_at ? new \DateTimeImmutable($row->created_at) : null,
                    updatedAt: $row->updated_at ? new \DateTimeImmutable($row->updated_at) : null
                );

                // Khởi tạo danh sách ảnh cho FoodCourt
                $foodCourt->images = [];
                $foodCourts[$fcId] = $foodCourt;
            }

            // Nếu có ảnh thì push vào mảng
            if ($row->image_id !== null) {
                $image = new FoodCourtImage(
                    id: $row->image_id,
                    foodCourtId: $fcId,
                    url: $row->image_url,
                    publicUrl: $row->image_public_url,
                    createdAt: $row->image_created_at ? new \DateTimeImmutable($row->image_created_at) : null,
                    updatedAt: $row->image_updated_at ? new \DateTimeImmutable($row->image_updated_at) : null
                );

                $foodCourts[$fcId]->images[] = $image;
            }
        }

        return array_values($foodCourts);
    }

    public function getFoodCourtsWithImagesByProvinceId($provinceId): array
    {
        $results = DB::table('food_courts')
            ->leftJoin('food_court_images', 'food_courts.id', '=', 'food_court_images.food_court_id')
            ->select(
                'food_courts.id as food_court_id',
                'food_courts.name',
                'food_courts.description',
                'food_courts.address',
                'food_courts.province_id',
                'food_courts.travel_spot_id',
                'food_courts.open_time',
                'food_courts.close_time',
                'food_courts.average_star',
                'food_courts.total_rates',
                'food_courts.price_from',
                'food_courts.price_to',
                'food_courts.created_at',
                'food_courts.updated_at',
                'food_court_images.id as image_id',
                'food_court_images.url as image_url',
                'food_court_images.public_url as image_public_url',
                'food_court_images.created_at as image_created_at',
                'food_court_images.updated_at as image_updated_at'
            )
            ->where('food_courts.province_id', '=', $provinceId) // 🟢 lọc theo tỉnh
            ->orderBy('food_courts.id', 'asc')
            ->get();

        $foodCourts = [];

        foreach ($results as $row) {
            $fcId = $row->food_court_id;

            // Nếu chưa khởi tạo FoodCourt object thì tạo mới
            if (!isset($foodCourts[$fcId])) {
                $foodCourt = new FoodCourt(
                    id: $fcId,
                    name: $row->name,
                    description: $row->description,
                    address: $row->address,
                    provinceId: $row->province_id,
                    travelSpotId: $row->travel_spot_id,
                    openTime: $row->open_time,
                    closeTime: $row->close_time,
                    averageStar: $row->average_star,
                    totalRates: $row->total_rates,
                    priceFrom: $row->price_from,
                    priceTo: $row->price_to,
                    createdAt: $row->created_at ? new \DateTimeImmutable($row->created_at) : null,
                    updatedAt: $row->updated_at ? new \DateTimeImmutable($row->updated_at) : null
                );

                $foodCourt->images = [];
                $foodCourts[$fcId] = $foodCourt;
            }

            // Nếu có ảnh thì thêm vào mảng images
            if ($row->image_id !== null) {
                $image = new FoodCourtImage(
                    id: $row->image_id,
                    foodCourtId: $fcId,
                    url: $row->image_url,
                    publicUrl: $row->image_public_url,
                    createdAt: $row->image_created_at ? new \DateTimeImmutable($row->image_created_at) : null,
                    updatedAt: $row->image_updated_at ? new \DateTimeImmutable($row->image_updated_at) : null
                );

                $foodCourts[$fcId]->images[] = $image;
            }
        }

        return array_values($foodCourts);
    }

    public function getFoodCourtsWithImagesByTravelSpotId($idTravelSpot): array
    {
        $results = DB::table('food_courts')
            ->leftJoin('food_court_images', 'food_courts.id', '=', 'food_court_images.food_court_id')
            ->select(
                'food_courts.id as food_court_id',
                'food_courts.name',
                'food_courts.description',
                'food_courts.address',
                'food_courts.province_id',
                'food_courts.travel_spot_id',
                'food_courts.open_time',
                'food_courts.close_time',
                'food_courts.average_star',
                'food_courts.total_rates',
                'food_courts.price_from',
                'food_courts.price_to',
                'food_courts.created_at as food_court_created_at',
                'food_courts.updated_at as food_court_updated_at',
                'food_court_images.id as image_id',
                'food_court_images.url',
                'food_court_images.public_url',
                'food_court_images.created_at as image_created_at',
                'food_court_images.updated_at as image_updated_at'
            )
            ->where('food_courts.travel_spot_id', $idTravelSpot)
            ->get();

        if ($results->isEmpty()) {
            return [];
        }

        $foodCourts = [];

        foreach ($results as $row) {
            $foodCourtId = $row->food_court_id;

            // Nếu chưa tồn tại thì khởi tạo mới
            if (!isset($foodCourts[$foodCourtId])) {
                $foodCourt = new FoodCourt(
                    id:             $row->food_court_id,
                    name:           $row->name,
                    description:    $row->description,
                    address:        $row->address,
                    provinceId:     $row->province_id,
                    travelSpotId:   $row->travel_spot_id,
                    openTime:       $row->open_time,
                    closeTime:      $row->close_time,
                    averageStar:    $row->average_star,
                    totalRates:     $row->total_rates,
                    priceFrom:      $row->price_from,
                    priceTo:        $row->price_to,
                    createdAt:      $row->food_court_created_at ? new \DateTimeImmutable($row->food_court_created_at) : null,
                    updatedAt:      $row->food_court_updated_at ? new \DateTimeImmutable($row->food_court_updated_at) : null
                );

                $foodCourt->images = [];
                $foodCourts[$foodCourtId] = $foodCourt;
            }

            // Nếu có ảnh thì thêm vào mảng images
            if ($row->image_id !== null) {
                $image = new FoodCourtImage(
                    id:           $row->image_id,
                    foodCourtId:  $row->food_court_id,
                    url:          $row->url,
                    publicUrl:    $row->public_url,
                    createdAt:    $row->image_created_at ? new \DateTimeImmutable($row->image_created_at) : null,
                    updatedAt:    $row->image_updated_at ? new \DateTimeImmutable($row->image_updated_at) : null
                );

                $foodCourts[$foodCourtId]->images[] = $image;
            }
        }

        // Reset key để trả về dạng mảng liên tục
        return array_values($foodCourts);
    }

    public function getFoodCourtById($id)
    {
        $results = DB::table('food_courts')
            ->leftJoin('food_court_images', 'food_courts.id', '=', 'food_court_images.food_court_id')
            ->select(
                'food_courts.id as food_court_id',
                'food_courts.name',
                'food_courts.description',
                'food_courts.address',
                'food_courts.province_id',
                'food_courts.travel_spot_id',
                'food_courts.open_time',
                'food_courts.close_time',
                'food_courts.average_star',
                'food_courts.total_rates',
                'food_courts.price_from',
                'food_courts.price_to',
                'food_courts.created_at as food_court_created_at',
                'food_courts.updated_at as food_court_updated_at',
                'food_court_images.id as image_id',
                'food_court_images.url',
                'food_court_images.public_url',
                'food_court_images.created_at as image_created_at',
                'food_court_images.updated_at as image_updated_at'
            )
            ->where('food_courts.id', $id)
            ->get();

        if ($results->isEmpty()) {
            return null;
        }

        $foodCourt = null;

        foreach ($results as $row) {
            if ($foodCourt === null) {
                $foodCourt = new FoodCourt(
                    id:             $row->food_court_id,
                    name:           $row->name,
                    description:    $row->description,
                    address:        $row->address,
                    provinceId:     $row->province_id,
                    travelSpotId:   $row->travel_spot_id,
                    openTime:       $row->open_time,
                    closeTime:      $row->close_time,
                    averageStar:    $row->average_star,
                    totalRates:     $row->total_rates,
                    priceFrom:      $row->price_from,
                    priceTo:        $row->price_to,
                    createdAt:      $row->food_court_created_at ? new \DateTimeImmutable($row->food_court_created_at) : null,
                    updatedAt:      $row->food_court_updated_at ? new \DateTimeImmutable($row->food_court_updated_at) : null
                );

                $foodCourt->images = [];
            }

            if ($row->image_id !== null) {
                $image = new FoodCourtImage(
                    id:           $row->image_id,
                    foodCourtId:  $row->food_court_id,
                    url:          $row->url,
                    publicUrl:    $row->public_url,
                    createdAt:    $row->image_created_at ? new \DateTimeImmutable($row->image_created_at) : null,
                    updatedAt:    $row->image_updated_at ? new \DateTimeImmutable($row->image_updated_at) : null
                );

                $foodCourt->images[] = $image;
            }
        }

        return $foodCourt->toArray();
    }

    public function getProvinceByFoodCourtId($foodCourtId)
    {
        return DB::table('food_courts')
            ->join('provinces', 'provinces.id', '=', 'food_courts.province_id')
            ->where('food_courts.id', $foodCourtId)
            ->select('provinces.id', 'provinces.name')
            ->first(); // trả về 1 object
    }

}
