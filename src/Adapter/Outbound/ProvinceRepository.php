<?php
namespace App\Adapter\Outbound;

use Illuminate\Database\Capsule\Manager as DB;
use App\Application\Port\Outbound\ProvincesRepositoryPort;
use App\Domain\Entity\Province;

class ProvinceRepository implements ProvincesRepositoryPort {
    public function save(Province $province): void {
        DB::table('provinces')->insert($province->toArray());
    }
}
