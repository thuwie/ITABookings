<?php

namespace App\Application\Port\Inbound;

use App\Domain\Entity\Provider;

interface ProviderServicePort {
    public function save($provider, $logo): bool;
    public function getRegisterForm(): array;
    public function getProviders($filter_value): array;
    public function getProviderById(int $id): array;
    public function saveVehicle($vehicleInfo, $imgs, $id): bool;
}