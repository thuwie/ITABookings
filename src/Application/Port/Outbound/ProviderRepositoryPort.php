<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Provider;
use App\Domain\Entity\Vehicle;
use App\Domain\Entity\CostsRelatedProvider;
use Psr\Http\Message\UploadedFileInterface;
interface ProviderRepositoryPort {
    public function save(Provider $provider): Provider;
    public function saveLogo(?UploadedFileInterface $logo, string $providerName): string;
    public function savePathLogo($url, Provider $provider): bool;
    public function update(Provider $provider): bool;
    public function findByUserIdWithVerifyFallback(int $userId): ?Provider;
    public function getProvidersByVerified(?bool $verified = null): array;
    public function findById(int $id): ?Provider;
    public function saveVehicle(Vehicle $vehicle): array;
    public function saveVehicleImgs(array $vehicles): bool;
    public function saveUtilities(array $utilities): array;
    public function saveVehicleWithUtilities(array $data): bool;
    public function getUtilities(): array;
    public function saveProviderExtraCosts(CostsRelatedProvider $costsRelatedProvider): bool;
    public function getProvidersWithVehicles(?int $seat = null, ?int $provider = null): array;
    public function providersRelatedCosts () : array;
    public function getExtraCosts();
    public function getSeatCounting():array;
    public function getProviderWithVehicle($providerId, $vehicleId): array;
    public function saveVehicleStatus($vehicle_id): bool;
    public function getDriversByProvider($providerId) : array;
    public function getDriversByIds(array $driverIds): array;
    public function getDriverWorkingHistory(array $driverIds): array;
    public function getDriversAreNotInBookingSortByASC(array $ids):array;
    public function getOptimalDriver(): ?object;
    public function saveDriverWorkingHistory(array $data): bool;
    public function saveDriversTrips(array $data): bool;
    public function getVehiclesWithStatusByProviderId(int $providerId): array;
    public function getDriversByProviderId(int $providerId, ?bool $filter_value = null): array;
    public function updateDriver(int $providerId, int $driverId): ?array;
}
