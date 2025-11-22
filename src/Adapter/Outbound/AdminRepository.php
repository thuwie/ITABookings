<?php

namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\AdminRepositoryPort;
use App\Domain\Entity\ExtraCost;
use Psr\Http\Message\UploadedFileInterface;
use Illuminate\Database\Capsule\Manager as DB;
use App\Helper\FileHelper;

class AdminRepository implements AdminRepositoryPort {
   public function approveProvider(int $providerId)
    {
        $now = new \DateTimeImmutable();

        $updated = DB::table('providers')
            ->where('id', $providerId)
            ->update([
                'verified_at' => $now,
                'updated_at'  => $now
            ]);

        if ($updated === 0) {
            return null; // or throw exception
        }

        return DB::table('providers')->where('id', $providerId)->first();
    }

    public function saveExtraCosts(ExtraCost $extraCosts): bool
    {
        if ($extraCosts->getId() === 0) {
            // Insert case
            return DB::table('extra_costs')->insert(
                $extraCosts->toInsertArray()
            );
        }

        // Update case
        $updated = DB::table('extra_costs')
            ->where('id', $extraCosts->getId())
            ->update($extraCosts->toUpdateArray());

        return $updated > 0;
    }

    public function getExtraCost(): ?ExtraCost {
        $row = DB::table('extra_costs')->first();

        if (!$row) {
            return null; // no row exists
        }

        return ExtraCost::fromArray((array) $row);
        }

}
