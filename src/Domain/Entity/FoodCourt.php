<?php
namespace App\Domain\Entity;

class FoodCourt
{
    private int $id;
    private string $name;
    private ?string $description;
    private ?string $address;
    private int $provinceId;
    private ?int $travelSpotId;
    private ?string $openTime;
    private ?string $closeTime;
    private float $averageStar;
    private int $totalRates;
    private ?float $priceFrom;
    private ?float $priceTo;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    // --- Constructor ---
    public function __construct(
        int $id,
        string $name,
        ?string $description,
        ?string $address,
        int $provinceId,
        ?int $travelSpotId = null,
        ?string $openTime = null,
        ?string $closeTime = null,
        float $averageStar = 0.0,
        int $totalRates = 0,
        ?float $priceFrom = null,
        ?float $priceTo = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->address = $address;
        $this->provinceId = $provinceId;
        $this->travelSpotId = $travelSpotId;
        $this->openTime = $openTime;
        $this->closeTime = $closeTime;
        $this->averageStar = $averageStar;
        $this->totalRates = $totalRates;
        $this->priceFrom = $priceFrom;
        $this->priceTo = $priceTo;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
    
    /** @var FoodCourtImages[] */
    public array $images = [];

    // --- Getters ---
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): ?string { return $this->description; }
    public function getAddress(): ?string { return $this->address; }
    public function getProvinceId(): int { return $this->provinceId; }
    public function getTravelSpotId(): ?int { return $this->travelSpotId; }
    public function getOpenTime(): ?string { return $this->openTime; }
    public function getCloseTime(): ?string { return $this->closeTime; }
    public function getAverageStar(): float { return $this->averageStar; }
    public function getTotalRates(): int { return $this->totalRates; }
    public function getPriceFrom(): ?float { return $this->priceFrom; }
    public function getPriceTo(): ?float { return $this->priceTo; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }

    // --- Setters ---
    public function setName(string $name): void { $this->name = $name; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setAddress(?string $address): void { $this->address = $address; }
    public function setProvinceId(int $provinceId): void { $this->provinceId = $provinceId; }
    public function setTravelSpotId(?int $travelSpotId): void { $this->travelSpotId = $travelSpotId; }
    public function setOpenTime(?string $openTime): void { $this->openTime = $openTime; }
    public function setCloseTime(?string $closeTime): void { $this->closeTime = $closeTime; }
    public function setAverageStar(float $averageStar): void { $this->averageStar = $averageStar; }
    public function setTotalRates(int $totalRates): void { $this->totalRates = $totalRates; }
    public function setPriceFrom(?float $priceFrom): void { $this->priceFrom = $priceFrom; }
    public function setPriceTo(?float $priceTo): void { $this->priceTo = $priceTo; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void { $this->updatedAt = $updatedAt; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'province_id' => $this->provinceId,
            'travel_spot_id' => $this->travelSpotId,
            'open_time' => $this->openTime,
            'close_time' => $this->closeTime,
            'average_star' => $this->averageStar,
            'total_rates' => $this->totalRates,
            'price_from' => $this->priceFrom,
            'price_to' => $this->priceTo,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s') ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s') ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ];
    }
}
