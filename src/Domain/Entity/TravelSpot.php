<?php

namespace App\Domain\Entity;

class TravelSpot
{
    private ?int $id;
    private string $name;
    private ?string $description;
    private int $provinceId;
    private ?string $openTime;
    private ?string $closeTime;
    private ?float $averageRate;
    private ?float $priceFrom;
    private ?float $priceTo;
    private ?int $totalRates;
    private ?string $fullAddress;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    // --- Constructor chuẩn ---
    public function __construct(
        ?int $id,
        string $name,
        ?string $description,
        int $provinceId,
        ?string $openTime,
        ?string $closeTime,
        ?float $averageRate,
        ?float $priceFrom,
        ?float $priceTo,
        ?int $totalRates,
        ?string $fullAddress,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->provinceId = $provinceId;
        $this->openTime = $openTime;
        $this->closeTime = $closeTime;
        $this->averageRate = $averageRate;
        $this->priceFrom = $priceFrom;
        $this->priceTo = $priceTo;
        $this->totalRates = $totalRates;
        $this->fullAddress = $fullAddress;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /** @var TravelImage[] */
    public array $images = [];

    // --- Getters ---
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): ?string { return $this->description; }
    public function getProvinceId(): int { return $this->provinceId; }
    public function getOpenTime(): ?string { return $this->openTime; }
    public function getCloseTime(): ?string { return $this->closeTime; }
    public function getAverageRate(): ?float { return $this->averageRate; }
    public function getPriceFrom(): ?float { return $this->priceFrom; }
    public function getPriceTo(): ?float { return $this->priceTo; }
    public function getTotalRates(): ?int { return $this->totalRates; }
    public function getFullAddress(): ?string { return $this->fullAddress; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }

    // --- Setters ---
    public function setId(?int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setProvinceId(int $provinceId): void { $this->provinceId = $provinceId; }
    public function setOpenTime(?string $openTime): void { $this->openTime = $openTime; }
    public function setCloseTime(?string $closeTime): void { $this->closeTime = $closeTime; }
    public function setAverageRate(?float $averageRate): void { $this->averageRate = $averageRate; }
    public function setPriceFrom(?float $priceFrom): void { $this->priceFrom = $priceFrom; }
    public function setPriceTo(?float $priceTo): void { $this->priceTo = $priceTo; }
    public function setTotalRates(?int $totalRates): void { $this->totalRates = $totalRates; }
    public function setFullAddress(?string $fullAddress): void { $this->fullAddress = $fullAddress; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void { $this->updatedAt = $updatedAt; }

    // --- toArray() giống Province ---
    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'province_id'   => $this->provinceId,
            'open_time'     => $this->openTime,
            'close_time'    => $this->closeTime,
            'average_rate'  => $this->averageRate,
            'price_from'    => $this->priceFrom,
            'price_to'      => $this->priceTo,
            'total_rates'   => $this->totalRates,
            'full_address'  => $this->fullAddress,
            'created_at'    => $this->createdAt?->format('Y-m-d H:i:s') ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updatedAt?->format('Y-m-d H:i:s') ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ];
    }
}
