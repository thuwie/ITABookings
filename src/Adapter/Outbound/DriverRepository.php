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
            experience: $driver->getExperience(),
            averageRates: $driver->getAverageRates(),
            ratingCount: $driver->getRatingCount(),
            verifiedAt: $driver->getVerifiedAt(),
            createdAt: $driver->getCreatedAt(),
            updatedAt: $driver->getUpdatedAt(),
        );
    }

    public function findUnVerifiedAccountByUserId(int $userId): ?Driver
    {
        $row = DB::table('drivers')->where('user_id', $userId)->where('verified_at', null)->first();
        if (!$row) return null;

        return Driver::fromArray((array)$row);
    }
}
