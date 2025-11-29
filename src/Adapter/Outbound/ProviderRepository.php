<?php

namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Domain\Entity\Provider;
use App\Domain\Entity\Vehicle;
use App\Domain\Entity\Driver;
use App\Domain\Entity\Utility;
use App\Domain\Entity\CostsRelatedProvider;
use Psr\Http\Message\UploadedFileInterface;
use Illuminate\Database\Capsule\Manager as DB;
use App\Helper\FileHelper;
use Illuminate\Support\Carbon;


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

    public function findByUserIdWithVerifyFallback(int $userId): ?Provider
    {
        // 1. Tìm user chưa verify
        $row = DB::table('providers')
            ->where('user_id', $userId)
            ->whereNull('verified_at')
            ->first();

        if (!$row) {
            // 2. Nếu không có → tìm user đã verify
            $row = DB::table('providers')
                ->where('user_id', $userId)
                ->whereNotNull('verified_at')
                ->first();
        }

        return $row ? Provider::fromArray((array)$row) : null;
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

   public function getDriversByProviderId(int $providerId, ?bool $filter_value = null): array {
        $query = DB::table('drivers')
            ->join('users', 'drivers.user_id', '=', 'users.id')
            ->select(
                'drivers.id as driver_id',
                'drivers.user_id',
                'drivers.provider_id',
                'drivers.license_number',
                'drivers.license_class',
                'drivers.license_issue_date',
                'drivers.license_expiry_date',
                'drivers.status as driver_status',
                'drivers.average_rates',
                'drivers.rating_count',
                'drivers.verified_at',
                'drivers.created_at',
                'drivers.updated_at',
                'users.first_name',
                'users.last_name',
                'users.date_of_birth'
            )
            ->where('drivers.provider_id', $providerId);

        if ($filter_value === true) {
            $query->whereNotNull('drivers.verified_at');
        } elseif ($filter_value === false) {
            $query->whereNull('drivers.verified_at');
        }

        $rows = $query->get();

        // Trả về mảng dữ liệu thuần
        return $rows->map(fn($row) => (array) $row)->toArray();
    }



    public function findById(int $id): ?Provider {
        $row = DB::table('providers')->where('id', $id)->first();
        if (!$row) return null;

        return Provider::fromArray((array)$row);
    }

   public function saveVehicle(Vehicle $vehicle): array
    {
        // 1️⃣ Insert into vehicles table
        $vehicleId = DB::table('vehicles')->insertGetId(
            $vehicle->toInsertArray()
        );

        // 2️⃣ Insert default status for this vehicle
        DB::table('vehicle_status')->insert([
            'vehicle_id' => $vehicleId,
            'status'     => 'available',   // mặc định khi tạo
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 3️⃣ Query the inserted vehicle row
        $newVehicle = DB::table('vehicles')
            ->where('id', $vehicleId)
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
            ->leftJoin('vehicle_status', 'vehicle_status.vehicle_id', '=', 'vehicles.id')
            ->select(
                'providers.user_id as provider_id',
                'providers.name as provider_name',
                'providers.logo_url as provider_logo',
                'vehicles.id as vehicle_id',
                DB::raw("CONCAT(vehicles.brand, ' ', vehicles.model) AS vehicle_name"),
                'vehicles.seat_count',
                'vehicles.fuel_consumption',
                'vehicles.maintenance_per_km'
            )
            ->where('vehicle_status.status', 'available');

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

                // Ảnh của xe
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

        // Convert utilities_list → array
        $utilities = [];
        if (!empty($row->utilities_list)) {
            $utilities = array_values(array_filter(array_map('trim', explode(',', $row->utilities_list))));
        }

        return [
            'provider_name' => $row->provider_name,
            'phone_number'  => $row->phone_number,
            'province_name' => $row->province_name,
            'brand'         => $row->brand,
            'model'         => $row->model,
            'seat_count'    => (int) $row->seat_count,
            'utilities'     => $utilities,
            'vehicle_img'   => $row->vehicle_img,
        ];
    }

    public function saveVehicleStatus($vehicle_id): bool 
    {
         try {
        return DB::table('vehicle_status')
            ->where('vehicle_id', $vehicle_id)
            ->update([
                'status'     => 'booked',
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $e) {
            // Log lỗi nếu cần
            // Log::error($e->getMessage());
            return false;
        }
    }

    public function getDriversByProvider($providerId) : array {
        // Lấy danh sách drivers theo provider_id
        $drivers = DB::table('drivers')
            ->where('provider_id', (int) $providerId)
            ->get();

        // Không có driver → trả về mảng rỗng
        if ($drivers->isEmpty()) {
            return [];
        }

        // Convert mỗi row stdClass thành array
        return $drivers->map(function ($item) {
            return (array) $item;
        })->toArray();
    }

    public function getDriversByIds(array $driverIds): array
    {
         return DB::table('drivers')
        ->whereIn('id', $driverIds)
        ->get()
        ->toArray();
    }

    public function getDriverWorkingHistory(array $driverIds): array
    {
        if (empty($driverIds)) {
            return [];
        }

        return DB::table('drivers_working_history')
            ->whereIn('driver_id', $driverIds)
            ->groupBy('driver_id')
            ->get()
            ->toArray();
    }

    public function getDriversAreNotInBookingSortByASC(array $ids):array {
       if (empty($ids)) {
        return [];
       };

       return DB::table('drivers')
        ->whereIn('id', $ids)
        ->whereNotIn('id', function ($query) {
            $query->select('driver_id')
                  ->from('drivers_working_history');
        })
        ->orderBy('id', 'ASC')  
        ->get()
        ->toArray();
    }

    public function getOptimalDriver(): ?object
    {
        $twoDaysAgo = Carbon::now()->subDays(2);

        return DB::table('drivers_working_history AS dwh')
            ->join('drivers AS d', 'd.id', '=', 'dwh.driver_id')

            ->where('dwh.status', 'available')
           

            // Ưu tiên 1: total_trips thấp nhất
            ->orderBy('dwh.total_trips', 'ASC')

            // Ưu tiên 2: lâu nhất chưa được assign
            ->orderBy('dwh.last_assigned', 'ASC')

            // Tie-break cuối: id tăng dần
            ->orderBy('dwh.driver_id', 'ASC')

            ->select(
                'd.*'
            )

            ->first(); // Chỉ trả về 1 driver tốt nhất
    }

    public function saveDriverWorkingHistory(array $data): bool
    {
        return DB::table('drivers_working_history')->insert([
            'driver_id'      => $data['driver_id'],
            'status'         => $data['status'] ?? 'idle',
            'total_trips'    => $data['total_trips'] ?? 0,
            'last_trip_end'  => $data['last_trip_end'] ?? null,
            'last_assigned'  => Carbon::now(),
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
        ]);
    }

    public function saveDriversTrips(array $data): bool
    {
        try {
            return DB::table('driver_trips')->insert([
                'driver_id'   => $data['driver_id'],
                'booking_id'  => $data['booking_id'],
                'status'      => $data['status'],
                'start_time'  => $data['start_time'],
                'end_time'    => $data['end_time'],
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ]);
        } catch (\Exception $e) {
            // log lỗi nếu cần
            // Log::error($e->getMessage());
            return false;
        }
    }

    public function getVehiclesWithStatusByProviderId(int $providerId): array
    {
        $rows = DB::table('vehicles as v')
            ->leftJoin('vehicle_status as vs', 'v.id', '=', 'vs.vehicle_id')
            ->where('v.provider_id', $providerId)
            ->orderBy('v.id', 'desc')
            ->select(
                'v.*',
                'vs.status as vehicle_status'
            )
            ->get();

        return $rows->toArray();
    }
}

