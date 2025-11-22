<?php
namespace App\Domain\Entity;

class CostsRelatedProvider
{
    private int $id;
    private int $providerId;            // provider_id
    private float $driverFeePerHour;    // driver_fee_per_hour
    private float $profitMargin;        // profit_margin
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        int $providerId,
        float $driverFeePerHour,
        float $profitMargin,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->providerId = $providerId;
        $this->driverFeePerHour = $driverFeePerHour;
        $this->profitMargin = $profitMargin;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    // ===== Setters =====
    public function setProviderId(int $providerId): void
    {
        $this->providerId = $providerId;
        $this->touch();
    }

    public function setDriverFeePerHour(float $fee): void
    {
        $this->driverFeePerHour = $fee;
        $this->touch();
    }

    public function setProfitMargin(float $margin): void
    {
        $this->profitMargin = $margin;
        $this->touch();
    }

    // ===== Getters =====
    public function getId(): int { return $this->id; }
    public function getProviderId(): int { return $this->providerId; }
    public function getDriverFeePerHour(): float { return $this->driverFeePerHour; }
    public function getProfitMargin(): float { return $this->profitMargin; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    // ===== Domain behavior =====
    public function updateValues(
        ?float $driverFeePerHour = null,
        ?float $profitMargin = null
    ): void {
        if ($driverFeePerHour !== null) $this->driverFeePerHour = $driverFeePerHour;
        if ($profitMargin !== null) $this->profitMargin = $profitMargin;
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
            'provider_id' => $this->providerId,
            'driver_fee_per_hour' => $this->driverFeePerHour,
            'profit_margin' => $this->profitMargin,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public function toInsertArray(): array
    {
        $arr = $this->toArray();
        unset($arr['id']); // auto-increment
        return $arr;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? 0,
            (int)($data['provider_id'] ?? 0),
            isset($data['driver_fee_per_hour']) ? (float)$data['driver_fee_per_hour'] : 0.0,
            isset($data['profit_margin']) ? (float)$data['profit_margin'] : 0.0,
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }
}
