<?php
namespace App\Domain\Entity;

class VehicleUtility
{
    private int $id;
    private int $vehicleId;
    private int $utilityId;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        int $vehicleId,
        int $utilityId,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->vehicleId = $vehicleId;
        $this->utilityId = $utilityId;
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

    public function setUtilityId(int $utilityId): void
    {
        $this->utilityId = $utilityId;
        $this->touch();
    }

    // ===== Getters =====
    public function getId(): int { return $this->id; }
    public function getVehicleId(): int { return $this->vehicleId; }
    public function getUtilityId(): int { return $this->utilityId; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    // ===== Domain Behavior =====
    public function updateRelationship(int $vehicleId, int $utilityId): void
    {
        $this->vehicleId = $vehicleId;
        $this->utilityId = $utilityId;
        $this->touch();
    }

    // ===== For Application Layer =====
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'vehicle_id' => $this->vehicleId,
            'utility_id' => $this->utilityId,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? 0,
            $data['vehicle_id'] ?? 0,
            $data['utility_id'] ?? 0,
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
