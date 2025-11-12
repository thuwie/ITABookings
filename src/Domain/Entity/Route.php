<?php
namespace App\Domain\Entity;

class Route
{
    private int $id;
    private string $fromLocationCode;
    private string $destinationCode;
    private string $name;
    private ?int $distanceKm;
    private ?int $durationMin;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        string $fromLocationCode,
        string $destinationCode,
        string $name,
        ?int $distanceKm = null,
        ?int $durationMin = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->fromLocationCode = $fromLocationCode;
        $this->destinationCode  = $destinationCode;
        $this->name             = $name;
        $this->distanceKm       = $distanceKm;
        $this->durationMin      = $durationMin;
        $this->createdAt        = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt        = $updatedAt ?? new \DateTimeImmutable();
    }

    // ===== Getters =====
    public function getId(): int { return $this->id; }
    public function getFromLocationCode(): string { return $this->fromLocationCode; }
    public function getDestinationCode(): string { return $this->destinationCode; }
    public function getName(): string { return $this->name; }
    public function getDistanceKm(): ?int { return $this->distanceKm; }
    public function getDurationMin(): ?int { return $this->durationMin; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    // ===== Domain Behaviors =====
    public function updateRoute(
        ?string $fromLocationCode = null,
        ?string $destinationCode = null,
        ?string $name = null,
        ?int $distanceKm = null,
        ?int $durationMin = null
    ): void {
        if ($fromLocationCode !== null) $this->fromLocationCode = $fromLocationCode;
        if ($destinationCode !== null) $this->destinationCode = $destinationCode;
        if ($name !== null) $this->name = $name;
        if ($distanceKm !== null) $this->distanceKm = $distanceKm;
        if ($durationMin !== null) $this->durationMin = $durationMin;
        $this->touch();
    }

    private function touch(): void {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // ===== For return to Application Layer =====
    public function toArray(): array {
        return [
            'id'                => $this->id,
            'from_location_code'=> $this->fromLocationCode,
            'destination_code'  => $this->destinationCode,
            'name'              => $this->name,
            'distance_km'       => $this->distanceKm,
            'duration_min'      => $this->durationMin,
            'created_at'        => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at'        => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? 0,
            $data['from_location_code'] ?? '',
            $data['destination_code'] ?? '',
            $data['name'] ?? '',
            $data['distance_km'] ?? null,
            $data['duration_min'] ?? null,
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }
}
