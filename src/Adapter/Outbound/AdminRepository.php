<?php

namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\AdminRepositoryPort;
use App\Domain\Entity\Provider;
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


}
