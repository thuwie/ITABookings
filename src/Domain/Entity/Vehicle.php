<?php
namespace App\Domain\Entity;

class Vehicle
{
    private int $id;
    private string $description;
    private string $licensePlate;
    private string $brand;
    private string $model;
    private int $yearOfManufacture;
    private int $seatCount;
    private int $providerId;
    private float $fuelConsumption;
    private float $maintenancePerKm;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        string $description,
        string $licensePlate,
        string $brand,
        string $model,
        int $yearOfManufacture,
        int $seatCount,
        int $providerId,
        float $fuelConsumption,
        float $maintenancePerKm,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->licensePlate = $licensePlate;
        $this->brand = $brand;
        $this->model = $model;
        $this->yearOfManufacture = $yearOfManufacture;
        $this->seatCount = $seatCount;
        $this->providerId = $providerId;
        $this->fuelConsumption = $fuelConsumption;
        $this->maintenancePerKm = $maintenancePerKm;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // ===== Setters =====
    public function setDescription(string $description): void
    {
        $this->description = $description;
        $this->touch();
    }

    public function setLicensePlate(string $licensePlate): void
    {
        $this->licensePlate = $licensePlate;
        $this->touch();
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
        $this->touch();
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
        $this->touch();
    }

    public function setYearOfManufacture(int $year): void
    {
        $this->yearOfManufacture = $year;
        $this->touch();
    }

    public function setSeatCount(int $seatCount): void
    {
        $this->seatCount = $seatCount;
        $this->touch();
    }

    public function setProviderId(int $providerId): void
    {
        $this->providerId = $providerId;
        $this->touch();
    }

    public function setFuelConsumption(float $fuel): void
    {
        $this->fuelConsumption = $fuel;
        $this->touch();
    }

    public function setMaintenancePerKm(float $value): void
    {
        $this->maintenancePerKm = $value;
        $this->touch();
    }

    // ===== Getters =====
    public function getId(): int { return $this->id; }
    public function getDescription(): string { return $this->description; }
    public function getLicensePlate(): string { return $this->licensePlate; }
    public function getBrand(): string { return $this->brand; }
    public function getModel(): string { return $this->model; }
    public function getYearOfManufacture(): int { return $this->yearOfManufacture; }
    public function getSeatCount(): int { return $this->seatCount; }
    public function getProviderId(): int { return $this->providerId; }
    public function getFuelConsumption(): float { return $this->fuelConsumption; }
    public function getMaintenancePerKm(): float { return $this->maintenancePerKm; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    // ===== Domain Behavior =====
    public function update(
        ?string $description = null,
        ?string $licensePlate = null,
        ?string $brand = null,
        ?string $model = null,
        ?int $yearOfManufacture = null,
        ?int $seatCount = null,
        ?float $fuelConsumption = null,
        ?float $maintenancePerKm = null
    ): void {
        if ($description !== null) $this->description = $description;
        if ($licensePlate !== null) $this->licensePlate = $licensePlate;
        if ($brand !== null) $this->brand = $brand;
        if ($model !== null) $this->model = $model;
        if ($yearOfManufacture !== null) $this->yearOfManufacture = $yearOfManufacture;
        if ($seatCount !== null) $this->seatCount = $seatCount;
        if ($fuelConsumption !== null) $this->fuelConsumption = $fuelConsumption;
        if ($maintenancePerKm !== null) $this->maintenancePerKm = $maintenancePerKm;

        $this->touch();
    }

    // ===== For Application Layer =====
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'license_plate' => $this->licensePlate,
            'brand' => $this->brand,
            'model' => $this->model,
            'year_of_manufacture' => $this->yearOfManufacture,
            'seat_count' => $this->seatCount,
            'provider_id' => $this->providerId,
            'fuel_consumption' => $this->fuelConsumption,
            'maintenance_per_km' => $this->maintenancePerKm,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? 0,
            $data['description'] ?? '',
            $data['license_plate'] ?? '',
            $data['brand'] ?? '',
            $data['model'] ?? '',
            (int)($data['year_of_manufacture'] ?? 0),
            (int)($data['seat_count'] ?? 0),
            (int)($data['provider_id'] ?? 0),
            (float)($data['fuel_consumption'] ?? 0),
            (float)($data['maintenance_per_km'] ?? 0),
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
