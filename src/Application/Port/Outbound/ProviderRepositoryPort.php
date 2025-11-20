<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Provider;
use App\Domain\Entity\Vehicle;
use Psr\Http\Message\UploadedFileInterface;
interface ProviderRepositoryPort {
    public function save(Provider $provider): Provider;
    public function saveLogo(?UploadedFileInterface $logo, string $providerName): string;
    public function savePathLogo($url, Provider $provider): bool;
    public function update(Provider $provider): bool;
    public function findUnVerifiedAccountByUserId (int $userId): ?Provider;
    public function getProvidersByVerified(?string $verifiedAt = null): array;
    public function findById(int $id): ?Provider;
    public function saveVehicle(Vehicle $vehicle): array;
    public function saveVehicleImgs(array $vehicles): bool;
    public function saveUtilities(array $utilities): array;
    public function saveVehicleWithUtilities(array $data): bool;
}
