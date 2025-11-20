<?php
// src/Infrastructure/Adapters/Persistence/UserRepository.php
namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\UploadImageRepositoryPort;
use App\Domain\Entity\InformationPayment;
use Psr\Http\Message\UploadedFileInterface;
use Illuminate\Database\Capsule\Manager as DB;
use App\Helper\FileHelper;

class UploadImageRepository implements UploadImageRepositoryPort {
    public function saveOne(UploadedFileInterface $file, string $category, string $nameFolder): string
    {
           $folderName = FileHelper::sanitizeFolderName($nameFolder);
        
        // Đặt thư mục upload tương đối (trong src/uploads/providers)
        $uploadDir = __DIR__ . "/../../../uploads/{$category}/{$folderName}/";

        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Đảm bảo chỉ xử lý file hợp lệ
        if ($file->getError() === UPLOAD_ERR_OK) {
            $originalName = $file->getClientFilename();

            $safeCategory = substr($category, 0, -1); // cắt ký tự cuối
            // Tạo tên file an toàn + duy nhất
            $safeName = uniqid("{$safeCategory}" .'_', true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

            // Đường dẫn đầy đủ
            $filePath = $uploadDir . $safeName;

            // Di chuyển file tạm đến thư mục upload
            $file->moveTo($filePath);

            // Lưu lại thông tin file
            $savedFiles = "/uploads/{$category}/{$folderName}/" . $safeName;
        }

        return $savedFiles;
    }

    
     public function saveMultipleOnes(array $files, string $category, string $nameFolder): array {
        $savedFiles = [];
        $folderName = FileHelper::sanitizeFolderName($nameFolder);
        
        // Đặt thư mục upload tương đối (trong src/uploads/provinces)
        $uploadDir = __DIR__ . "/../../../uploads/{$category}/{$folderName}/";

        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }


        foreach ($files as $img) {
            // Đảm bảo chỉ xử lý file hợp lệ
            if ($img->getError() === UPLOAD_ERR_OK) {
                $originalName = $img->getClientFilename();

                // Tạo tên file an toàn + duy nhất
                $safeName = uniqid($folderName, true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

                // Đường dẫn đầy đủ
                $filePath = $uploadDir . $safeName;

                // Di chuyển file tạm đến thư mục upload
                $img->moveTo($filePath);

                // Lưu lại thông tin file
                $savedFiles[] = [
                    'original_name' => $originalName,
                    'file_name'     => $safeName,
                    'file_path'     => $filePath,
                    'url'           => "/uploads/{$category}/{$folderName}/" . $safeName,
                ];
            }
        }

        return $savedFiles;
     }

}
