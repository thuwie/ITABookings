<?php

namespace App\Domain\Entity;

class TravelSpotImages
{
    private ?int $id;
    private int $travelSpotId;
    private string $url;
    private ?string $publicUrl;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        ?int $id,
        int $travelSpotId,
        string $url,
        ?string $publicUrl = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->travelSpotId = $travelSpotId;
        $this->url = $url;
        $this->publicUrl = $publicUrl;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // ✅ Getters & Setters
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getTravelSpotId(): int { return $this->travelSpotId; }
    public function setTravelSpotId(int $travelSpotId): void { $this->travelSpotId = $travelSpotId; }

    public function getUrl(): string { return $this->url; }
    public function setUrl(string $url): void { $this->url = $url; }

    public function getPublicUrl(): ?string { return $this->publicUrl; }
    public function setPublicUrl(?string $publicUrl): void { $this->publicUrl = $publicUrl; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void { $this->createdAt = $createdAt; }

    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void { $this->updatedAt = $updatedAt; }
}
