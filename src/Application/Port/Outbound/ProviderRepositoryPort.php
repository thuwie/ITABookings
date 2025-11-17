<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\Provider;
use Psr\Http\Message\UploadedFileInterface;
interface ProviderRepositoryPort {
    public function save(Provider $provider): Provider;
    public function saveLogo(?UploadedFileInterface $logo, string $providerName): string;
    public function savePathLogo($url, Provider $provider): bool;
    public function update(Provider $provider): bool;
    public function findUnVerifiedAccountByUserId (int $userId): ?Provider;
    public function getProvidersByVerified(?string $verifiedAt = null): array;
    public function findById(int $id): ?Provider;
}
