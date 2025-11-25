<?php
namespace App\Domain\Entity;

class Booking
{
    private int $id;
    private int $userId;
    private int $providerId;
    private int $vehicleId;

    private string $fromLocation;
    private string $destination;
    private int $distance;

    private \DateTimeImmutable $fromDate;
    private \DateTimeImmutable $toDate;

    private int $totalDays;

    private float $totalAmount;

    private string $status;

    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        int $userId,
        int $providerId,
        int $vehicleId,
        string $fromLocation,
        string $destination,
        int $distance,
        \DateTimeImmutable $fromDate,
        \DateTimeImmutable $toDate,
        int $totalDays,
        float $totalAmount,
        string $status,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->providerId = $providerId;
        $this->vehicleId = $vehicleId;
        $this->fromLocation = $fromLocation;
        $this->destination = $destination;
        $this->distance = $distance;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->totalDays = $totalDays;
        $this->totalAmount = $totalAmount;
        $this->status = $status;

        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    // ====================
    //        Getters
    // ====================
    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getProviderId(): int { return $this->providerId; }
    public function getVehicleId(): int { return $this->vehicleId; }
    public function getFromLocation(): string { return $this->fromLocation; }
    public function getDestination(): string { return $this->destination; }
    public function getDistance(): ?float { return $this->distance; }
    public function getFromDate(): \DateTimeImmutable { return $this->fromDate; }
    public function getToDate(): \DateTimeImmutable { return $this->toDate; }
    public function getTotalDays(): int { return $this->totalDays; }
    public function getTotalAmount(): float { return $this->totalAmount; }
    public function getStatus(): string { return $this->status; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    // ====================
    //        Setters
    // ====================
    public function setUserId(int $userId): void {
        $this->userId = $userId;
        $this->touch();
    }

    public function setProviderId(int $providerId): void {
        $this->providerId = $providerId;
        $this->touch();
    }

    public function setVehicleId(int $vehicleId): void {
        $this->vehicleId = $vehicleId;
        $this->touch();
    }

    public function setFromLocation(string $loc): void {
        $this->fromLocation = $loc;
        $this->touch();
    }

    public function setDestination(string $dest): void {
        $this->destination = $dest;
        $this->touch();
    }

    public function setDistance(int $distance): void {
        $this->distance = $distance;
        $this->touch();
    }

    public function setFromDate(\DateTimeImmutable $date): void {
        $this->fromDate = $date;
        $this->touch();
    }

    public function setToDate(\DateTimeImmutable $date): void {
        $this->toDate = $date;
        $this->touch();
    }

    public function setTotalDays(int $days): void {
        $this->totalDays = $days;
        $this->touch();
    }

    public function setTotalAmount(float $amount): void {
        $this->totalAmount = $amount;
        $this->touch();
    }

    public function setStatus(string $status): void {
        $this->status = $status;
        $this->touch();
    }

    // ====================
    //   Internal Update
    // ====================
    private function touch(): void {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // ====================
    //   Conversions
    // ====================
    public function toArray(): array {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'provider_id' => $this->providerId,
            'vehicle_id' => $this->vehicleId,
            'from_location' => $this->fromLocation,
            'destination' => $this->destination,
            'distance' => $this->distance,
            'from_date' => $this->fromDate->format('Y-m-d H:i:s'),
            'to_date' => $this->toDate->format('Y-m-d H:i:s'),
            'total_days' => $this->totalDays,
            'total_amount' => $this->totalAmount,
            'status' => $this->status,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? 0,
            $data['user_id'],
            $data['provider_id'],
            $data['vehicle_id'],
            $data['from_location'],
            $data['destination'],
            $data['distance'] ?? null,
            new \DateTimeImmutable($data['from_date']),
            new \DateTimeImmutable($data['to_date']),
            $data['total_days'],
            $data['total_amount'],
            $data['status'],
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }

    public function toInsertArray(): array {
        $arr = $this->toArray();
        unset($arr['id']); // auto increment
        return $arr;
    }
}
