<?php

namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Domain\Entity\Provider;
use App\Domain\Entity\Vehicle;
use App\Domain\Entity\Utility;
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
            email: $provider->getEmail(),
            logoUrl: $provider->getLogoUrl(),
            description: $provider->getDescription(),
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

    public function findUnVerifiedAccountByUserId(int $userId): ?Provider
    {
        $row = DB::table('providers')->where('user_id', $userId)->where('verified_at', null)->first();
        if (!$row) return null;

        return Provider::fromArray((array)$row);
    }

    public function getProvidersByVerified(?bool $verified = null): array {
        $query = DB::table('providers');

        if ($verified === true) {
            $query->whereNotNull('verified_at');
        } elseif ($verified === false) {
            $query->whereNull('verified_at'); // <-- filter for null
        }

        $rows = $query->get();
        return array_map(fn($row) => Provider::fromArray((array)$row), $rows->toArray());
    }


    public function findById(int $id): ?Provider {
        $row = DB::table('providers')->where('id', $id)->first();
        if (!$row) return null;

        return Provider::fromArray((array)$row);
    }

   public function saveVehicle(Vehicle $vehicle): array {
        // Insert and get last ID
        $id = DB::table('vehicles')->insertGetId(
        $vehicle->toInsertArray()
        );

        // Query the inserted row
        $newVehicle = DB::table('vehicles')
        ->where('id', $id)
        ->first();

        return (array) $newVehicle;
   }

   public function saveVehicleImgs(array $vehicles): bool {
        $rows = [];

        foreach ($vehicles as $vehicleImage) {
            $rows[] = $vehicleImage->toInsertArray();
        }

        $result = DB::table('vehicle_imgs')->insert($rows);
        return $result;
   }
   
    public function saveUtilities(array $utilities): array
    {
        $insertedIds = [];

        foreach ($utilities as $utility) {
            if (!$utility instanceof Utility) {
                throw new \InvalidArgumentException("Expected Utility entity");
            }

            $id = DB::table('utilities')->insertGetId($utility->toInsertArray());
            $insertedIds[] = $id;
        }

        return $insertedIds; // array of inserted IDs
    }


   public function saveVehicleWithUtilities(array $data): bool {
        $rows = [];

        foreach ($data as $vehicleImage) {
            $rows[] = $vehicleImage->toInsertArray();
        }

        $result = DB::table('vehicle_utilities')->insert($rows);
        return $result;
   }

   public function getUtilities(): array {
        $result = DB::table('utilities')->get();
        return $result->toArray(); 
   }
}

