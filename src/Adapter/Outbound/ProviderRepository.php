<?php
// src/Infrastructure/Adapters/Persistence/UserRepository.php
namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Domain\Entity\Provider;
use Psr\Http\Message\UploadedFileInterface;
use Illuminate\Database\Capsule\Manager as DB;
use App\Helper\FileHelper;

class ProviderRepository implements ProviderRepositoryPort {
    public function save(Provider $provider): Provider
    {
        $id = DB::table('providers')->insertGetId($provider->toInsertArray());
        
        // Tạo lại entity với ID vừa sinh ra
        return new Provider(
            id: $id,
            userId: $provider->getUserId(),
            name: $provider->getName(),
            logoUrl: $provider->getLogoUrl(),
            description: $provider->getDescription(),
            email: $provider->getEmail(),
            phoneNumber: $provider->getPhoneNumber(),
            address: $provider->getAddress(),
            provinceId: $provider->getProvinceId(),
            averageRates: $provider->getAverageRates(),
            ratingCount: $provider->getRatingCount(),
            verifiedAt: $provider->getVerifiedAt(),
            createdAt: $provider->getCreatedAt(),
            updatedAt: $provider->getUpdatedAt(),
        );
    }


    public function saveLogo(?UploadedFileInterface $logo, string $providerName): string {
        $folderName = FileHelper::sanitizeFolderName($providerName);
        
        // Đặt thư mục upload tương đối (trong src/uploads/providers)
        $uploadDir = __DIR__ . "/../../../uploads/providers/{$folderName}/";

        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Đảm bảo chỉ xử lý file hợp lệ
        if ($logo->getError() === UPLOAD_ERR_OK) {
            $originalName = $logo->getClientFilename();

            // Tạo tên file an toàn + duy nhất
            $safeName = uniqid('provider_', true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

            // Đường dẫn đầy đủ
            $filePath = $uploadDir . $safeName;

            // Di chuyển file tạm đến thư mục upload
            $logo->moveTo($filePath);

            // Lưu lại thông tin file
            $savedFiles = "/uploads/providers/{$folderName}/" . $safeName;
        }

        return $savedFiles;
    }

    public function savePathLogo($url, Provider $provider): bool {
        $updateLogoProvider = $provider;
        $updateLogoProvider->setLogoUrl($url);
        if($updateLogoProvider) {
            $result = $this->update($updateLogoProvider);
            return $result;
        }
        return false;
    }

    public function update(Provider $provider): bool
    {
        $data = $provider->toInsertArray();
        unset($data['created_at']); // không cập nhật created_at

        $updated = DB::table('providers')
            ->where('id', $provider->getId()) // sử dụng getter lấy ID
            ->update($data);

        return $updated > 0; // true nếu có ít nhất 1 bản ghi bị update
    }


}
