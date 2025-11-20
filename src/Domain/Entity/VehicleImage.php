<?php
namespace App\Domain\Entity;

class VehicleImage
{
    private int $id;
    private int $vehicleId;
    private string $url;
    private ?string $publicUrl;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        int $vehicleId,
        string $url,
        ?string $publicUrl = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->vehicleId = $vehicleId;
        $this->url = $url;
        $this->publicUrl = $publicUrl;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // ===== Setters =====
    public function setVehicleId(int $vehicleId): void
    {
        $this->vehicleId = $vehicleId;
        $this->touch();
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
        $this->touch();
    }

    public function setPublicUrl(?string $publicUrl): void
    {
        $this->publicUrl = $publicUrl;
        $this->touch();
    }

    // ===== Getters =====
    public function getId(): int { return $this->id; }
    public function getVehicleId(): int { return $this->vehicleId; }
    public function getUrl(): string { return $this->url; }
    public function getPublicUrl(): ?string { return $this->publicUrl; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    // ===== Domain Behavior =====
    public function updateImage(?string $url = null, ?string $publicUrl = null): void
    {
        if ($url !== null) $this->url = $url;
        if ($publicUrl !== null) $this->publicUrl = $publicUrl;

        $this->touch();
    }

    // ===== For Application Layer =====
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'vehicle_id' => $this->vehicleId,
            'url' => $this->url,
            'public_url' => $this->publicUrl,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? 0,
            $data['vehicle_id'] ?? 0,
            $data['url'] ?? '',
            $data['public_url'] ?? null,
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }

    public function toInsertArray(): array
    {
        $arr = $this->toArray();
        unset($arr['id']);  // auto-increment
        return $arr;
    }
}
