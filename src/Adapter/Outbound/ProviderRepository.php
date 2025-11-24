<?php

namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Domain\Entity\Provider;
use App\Domain\Entity\Vehicle;
use App\Domain\Entity\Utility;
use App\Domain\Entity\CostsRelatedProvider;
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

    public function saveProviderExtraCosts(CostsRelatedProvider $costsRelatedProvider): bool {
        $result = DB::table('costs_related_providers')->insertGetId($costsRelatedProvider->toInsertArray());
         return $result > 0;
    }

    public function getProvidersWithVehicles(?int $seat = null, ?int $provider = null): array
    {
        $query = DB::table('providers')
            ->join('vehicles', 'vehicles.provider_id', '=', 'providers.user_id')
            ->select(
                'providers.user_id as provider_id',
                'providers.name as provider_name',
                'providers.logo_url as provider_logo',
                'vehicles.id as vehicle_id',
                DB::raw("CONCAT(vehicles.brand, ' ', vehicles.model) AS vehicle_name"),
                'vehicles.seat_count',
                'vehicles.fuel_consumption',
                'vehicles.maintenance_per_km'
            );

        $query->when($seat !== null, function ($q) use ($seat) {
            $q->where('vehicles.seat_count', $seat);
        });

        $query->when($provider !== null, function ($q) use ($provider) {
            $q->where('providers.user_id', $provider);
        });

        $result = $query->get();

        return $result->toArray();
    }


    public function providersRelatedCosts () : array {
        $costs = DB::table('costs_related_providers')
        ->orderBy('id', 'asc')
        ->get();

        return $costs->toArray();
    }

    public function getExtraCosts() {
        $extraCosts= DB::table('extra_costs')->first();
        return $extraCosts;
    }

    public function getSeatCounting():array {
        $seatCounting = DB::table('vehicles')
        ->select('seat_count')
        ->distinct()
        ->get();

        return $seatCounting->toArray();
    }

    public function getProviderWithVehicle($providerId, $vehicleId): array
    {
       
        $row = DB::table('providers')
        ->join('vehicles', 'vehicles.provider_id', '=', 'providers.user_id')
        ->leftJoin('provinces', 'provinces.id', '=', 'providers.province_id')
        ->leftJoin('vehicle_utilities', 'vehicle_utilities.vehicle_id', '=', 'vehicles.id')
        ->leftJoin('utilities', 'utilities.id', '=', 'vehicle_utilities.utility_id')
        ->where('providers.user_id', $providerId)
        ->where('vehicles.id', $vehicleId)
        ->select(
            'providers.name as provider_name',
            'providers.phone_number',
            'provinces.name as province_name',
            'vehicles.brand',
            'vehicles.model',
            'vehicles.seat_count',
            DB::raw("GROUP_CONCAT(utilities.name SEPARATOR ',') as utilities_list"),

            // Lấy một ảnh duy nhất của xe
            DB::raw("(
                SELECT url 
                FROM vehicle_imgs 
                WHERE vehicle_imgs.vehicle_id = vehicles.id 
                ORDER BY id ASC 
                LIMIT 1
            ) as vehicle_img")
        )
        ->groupBy(
            'providers.id',
            'providers.name',
            'providers.phone_number',
            'provinces.name',
            'vehicles.id',
            'vehicles.brand',
            'vehicles.model',
            'vehicles.seat_count'
        )
        ->first();


        if (!$row) {
            return [];
        }

        // Chuyển utilities_list (chuỗi) thành mảng, loại bỏ rỗng
        $utilities = [];
        if (!empty($row->utilities_list)) {
            $utilities = array_filter(array_map('trim', explode(',', $row->utilities_list)));
            // reset keys
            $utilities = array_values($utilities);
        }

        return [
            'provider_name' => $row->provider_name,
            'phone_number'  => $row->phone_number,
            'province_name' => $row->province_name,
            'brand'         => $row->brand,
            'model'         => $row->model,
            'seat_count'    => (int) $row->seat_count,
            'utilities'     => $utilities,
            'vehicle_img' => $row->vehicle_img,
        ];
    }


}

