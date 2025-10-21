<?php
namespace App\Domain\Entity;

class FoodCourtImage
{
    private int $id;
    private int $foodCourtId;
    private string $url;
    private ?string $publicUrl;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    // --- Constructor ---
    public function __construct(
        int $id,
        int $foodCourtId,
        string $url,
        ?string $publicUrl = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->foodCourtId = $foodCourtId;
        $this->url = $url;
        $this->publicUrl = $publicUrl;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // --- Getters ---
    public function getId(): int { return $this->id; }
    public function getFoodCourtId(): int { return $this->foodCourtId; }
    public function getUrl(): string { return $this->url; }
    public function getPublicUrl(): ?string { return $this->publicUrl; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }

    // --- Setters ---
    public function setFoodCourtId(int $foodCourtId): void { $this->foodCourtId = $foodCourtId; }
    public function setUrl(string $url): void { $this->url = $url; }
    public function setPublicUrl(?string $publicUrl): void { $this->publicUrl = $publicUrl; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void { $this->updatedAt = $updatedAt; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'food_court_id' => $this->foodCourtId,
            'url' => $this->url,
            'public_url' => $this->publicUrl,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s') ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s') ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ];
    }
}
