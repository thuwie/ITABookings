<?php

namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\DriverRepositoryPort;
use App\Domain\Entity\Driver;
use Psr\Http\Message\UploadedFileInterface;
use Illuminate\Database\Capsule\Manager as DB;
use App\Helper\FileHelper;

class DriverRepository implements DriverRepositoryPort {
    public function save(Driver $driver): Driver
    {
        $id = DB::table('drivers')->insertGetId($driver->toInsertArray());

        return new Driver(
            id: $id,
            userId: $driver->getUserId(),
            providerId: $driver->getProviderId(),
            licenseNumber: $driver->getLicenseNumber(),
            licenseClass: $driver->getLicenseClass(),
            licenseIssueDate: $driver->getLicenseIssueDate(),
            licenseExpiryDate: $driver->getLicenseExpiryDate(),
            status: $driver->getStatus(),
            averageRates: $driver->getAverageRates(),
            ratingCount: $driver->getRatingCount(),
            verifiedAt: $driver->getVerifiedAt(),
            createdAt: $driver->getCreatedAt(),
            updatedAt: $driver->getUpdatedAt(),
        );
    }

   public function findByUserIdWithVerifyFallback(int $userId): ?Driver
    {
        // 1. Ưu tiên tìm driver chưa duyệt
        $row = DB::table('drivers')
            ->where('user_id', $userId)
            ->whereNull('verified_at')
            ->first();

        // 2. Nếu không có → lấy driver đã duyệt
        if (!$row) {
            $row = DB::table('drivers')
                ->where('user_id', $userId)
                ->whereNotNull('verified_at')
                ->first();
        }

        return $row ? Driver::fromArray((array)$row) : null;
    }

}
