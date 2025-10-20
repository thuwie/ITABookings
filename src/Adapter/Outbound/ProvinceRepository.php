<?php
namespace App\Adapter\Outbound;

use Illuminate\Database\Capsule\Manager as DB;
use App\Application\Port\Outbound\ProvinceRepositoryPort;
use App\Domain\Entity\Province;
use App\Helper\FileHelper;
use Illuminate\Support\Carbon;
use App\Domain\Entity\ProvinceImages;

class ProvinceRepository implements ProvinceRepositoryPort {
    public function getProvinces():array {
         $results = DB::table('provinces')->get(); // Lấy toàn bộ dữ liệu
        $provinces = [];
        foreach ($results as $row) {
            $province = new Province(
                id:          $row->id,
                code:        $row->code,
                name:        $row->name,
                type:        $row->type,
                description: $row->description,
               createdAt: $row->created_at ? new \DateTimeImmutable($row->created_at) : null,
               updatedAt: $row->updated_at ? new \DateTimeImmutable($row->updated_at) : null
            );

            $provinces[] = $province;
        }

        return $provinces;
    }
    public function save(Province $province): array {
       $id = DB::table('provinces')->insertGetId([
        'code'       => $province->getCode(),
        'name'       => $province->getName(),
        'type'       => $province->getType(),
        'description' => $province->getDescription(),
        'created_at' => $province->getCreatedAt() ??  Carbon::now(),
        'updated_at' => $province->getUpdatedAt() ??  Carbon::now(),
    ]);

    $provinceArray = $province->toArray();
    $provinceArray['id'] = $id;

    return $provinceArray;
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

   public function findById(int $id): ?array
        {
            $row = DB::table('provinces')->where('id', $id)->first();
            if (!$row) {
                return null;
            }

            $user = new Province(
                id: $row->id,
                code: $row->code,
                name: $row->name,
                type: $row->type,
                description: $row->description,
                createdAt: $row->created_at ? new \DateTimeImmutable($row->created_at) : null,
                updatedAt: $row->updated_at ? new \DateTimeImmutable($row->updated_at) : null
            );

            return $user->toArray();
        }

    public function getProvincesWithImages(): array
    {
        // Lấy tất cả province kèm hình ảnh liên kết
        $results = DB::table('provinces')
            ->leftJoin('province_images', 'provinces.id', '=', 'province_images.province_id')
            ->select(
                'provinces.id as province_id',
                'provinces.code',
                'provinces.name',
                'provinces.type',
                'provinces.description',
                'provinces.created_at as province_created_at',
                'provinces.updated_at as province_updated_at',
                'province_images.id as image_id',
                'province_images.url',
                'province_images.publicUrl',
                'province_images.created_at as image_created_at',
                'province_images.updated_at as image_updated_at'
            )
            ->get();

        $provinces = [];

        foreach ($results as $row) {
            $provinceId = $row->province_id;

            // Nếu province chưa được khởi tạo thì tạo mới
            if (!isset($provinces[$provinceId])) {
                $provinces[$provinceId] = new Province(
                    id:          $row->province_id,
                    code:        $row->code,
                    name:        $row->name,
                    type:        $row->type,
                    description: $row->description,
                    createdAt:   $row->province_created_at ? new \DateTimeImmutable($row->province_created_at) : null,
                    updatedAt:   $row->province_updated_at ? new \DateTimeImmutable($row->province_updated_at) : null
                );

                // Khởi tạo mảng chứa ảnh
                $provinces[$provinceId]->images = [];
            }

            // Nếu có ảnh thì thêm vào mảng ảnh của Province
            if ($row->image_id) {
                $image = new ProvinceImages(
                    id:        $row->image_id,
                    provinceId: $row->province_id,
                    url:       $row->url,
                    publicUrl: $row->publicUrl,
                    createdAt: $row->image_created_at ? new \DateTimeImmutable($row->image_created_at) : null,
                    updatedAt: $row->image_updated_at ? new \DateTimeImmutable($row->image_updated_at) : null
                );

                $provinces[$provinceId]->images[] = $image;
            }
        }

        // Trả về dữ liệu dưới dạng mảng tuần tự
        return array_values($provinces);
    }

    public function getProvinceByIdWithImages($id)
    {
        // Lấy dữ liệu bằng LEFT JOIN
        $results = DB::table('provinces')
            ->leftJoin('province_images', 'provinces.id', '=', 'province_images.province_id')
            ->select(
                'provinces.id as province_id',
                'provinces.code',
                'provinces.name',
                'provinces.type',
                'provinces.description',
                'provinces.created_at',
                'provinces.updated_at',
                'province_images.id as image_id',
                'province_images.url as image_url',
                'province_images.publicUrl as image_public_url',
                'province_images.created_at as image_created_at',
                'province_images.updated_at as image_updated_at'
            )
            ->where('provinces.id', $id)
            ->get();

        // Nếu không tìm thấy province thì trả về null
        if ($results->isEmpty()) {
            return null;
        }

        $province = null;

        foreach ($results as $row) {
            if (!$province) {
                // Khởi tạo Province entity
                $province = new Province(
                    id:          $row->province_id,
                    code:        $row->code,
                    name:        $row->name,
                    type:        $row->type,
                    description: $row->description,
                    createdAt:   $row->created_at ? new \DateTimeImmutable($row->created_at) : null,
                    updatedAt:   $row->updated_at ? new \DateTimeImmutable($row->updated_at) : null
                );
                // Khởi tạo mảng ảnh
                $province->images = [];
            }

            // Nếu có ảnh thì push vào mảng
            if ($row->image_id !== null) {
                $image = new ProvinceImages(
                    id:         $row->image_id,
                    provinceId: $row->province_id,
                    url:        $row->image_url,
                    publicUrl:  $row->image_public_url,
                    createdAt:  $row->image_created_at ? new \DateTimeImmutable($row->image_created_at) : null,
                    updatedAt:  $row->image_updated_at ? new \DateTimeImmutable($row->image_updated_at) : null
                );

                $province->images[] = $image;
            }
        }

        return $province;
    }

}
