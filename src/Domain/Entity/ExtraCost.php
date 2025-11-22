<?php
namespace App\Domain\Entity;

class ExtraCost
{
    private int $id;
    private float $extraCost;              // extra_cost
    private float $platformFeePercent;     // platform_fee_percent
    private float $fuelPrice;              // fuel_price
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        float $extraCost,
        float $platformFeePercent,
        float $fuelPrice,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->extraCost = $extraCost;
        $this->platformFeePercent = $platformFeePercent;
        $this->fuelPrice = $fuelPrice;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    // ===== Setters =====
    public function setExtraCost(float $value): void
    {
        $this->extraCost = $value;
        $this->touch();
    }

    public function setPlatformFeePercent(float $value): void
    {
        $this->platformFeePercent = $value;
        $this->touch();
    }

    public function setFuelPrice(float $value): void
    {
        $this->fuelPrice = $value;
        $this->touch();
    }

    // ===== Getters =====
    public function getId(): int { return $this->id; }
    public function getExtraCost(): float { return $this->extraCost; }
    public function getPlatformFeePercent(): float { return $this->platformFeePercent; }
    public function getFuelPrice(): float { return $this->fuelPrice; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    // ===== Domain Behavior =====
    public function updateValues(
        ?float $extraCost = null,
        ?float $platformFeePercent = null,
        ?float $fuelPrice = null
    ): void {
        if ($extraCost !== null) $this->extraCost = $extraCost;
        if ($platformFeePercent !== null) $this->platformFeePercent = $platformFeePercent;
        if ($fuelPrice !== null) $this->fuelPrice = $fuelPrice;
        $this->touch();
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // ===== Array outputs =====
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'extra_cost' => $this->extraCost,
            'platform_fee_percent' => $this->platformFeePercent,
            'fuel_price' => $this->fuelPrice,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public function toUpdateArray(): array {
        return [
            'extra_cost'           => $this->extraCost,
            'platform_fee_percent' => $this->platformFeePercent,
            'fuel_price'           => $this->fuelPrice,
            'updated_at'           => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? 0,
            isset($data['extra_cost']) ? (float)$data['extra_cost'] : 0.0,
            isset($data['platform_fee_percent']) ? (float)$data['platform_fee_percent'] : 0.0,
            isset($data['fuel_price']) ? (float)$data['fuel_price'] : 0.0,
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }

    public function toInsertArray(): array
    {
        $arr = $this->toArray();
        unset($arr['id']); // auto-increment
        return $arr;
    }
}
