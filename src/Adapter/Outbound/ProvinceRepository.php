<?php
namespace App\Adapter\Outbound;

use Illuminate\Database\Capsule\Manager as DB;
use App\Application\Port\Outbound\ProvinceRepositoryPort;
use App\Domain\Entity\Province;
use App\Helper\FileHelper;
use Illuminate\Support\Carbon;

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

}
