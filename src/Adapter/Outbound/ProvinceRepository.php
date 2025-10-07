<?php
namespace App\Adapter\Outbound;

use Illuminate\Database\Capsule\Manager as DB;
use App\Application\Port\Outbound\ProvinceRepositoryPort;
use App\Domain\Entity\Province;

class ProvinceRepository implements ProvinceRepositoryPort {
    public function save(Province $province): array {
        DB::table('provinces')->insert($province->toArray());
        return $province->toArray();
    }
    public function saveProvinceImages(array $imgs): array
    {
        $savedFiles = [];
        
        // Đặt thư mục upload tương đối (trong src/uploads/provinces)
        $uploadDir = __DIR__ . '/../uploads/provinces/';

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
                $filePath = $this-> $uploadDir . $safeName;

                // Di chuyển file tạm đến thư mục upload
                $img->moveTo($filePath);

                // Lưu lại thông tin file
                $savedFiles[] = [
                    'original_name' => $originalName,
                    'file_name'     => $safeName,
                    'file_path'     => $filePath,
                    'url'           => '/uploads/provinces/' . $safeName, // để frontend dùng
                ];
            }
        }

        return $savedFiles;
    }
}
