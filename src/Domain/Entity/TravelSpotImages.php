<?php

namespace App\Domain\Entity;

class TravelSpotImages
{
    private ?int $id;
    private int $travelSpotId;
    private string $url;
    private ?string $publicUrl;

    public function __construct()
    {
        $this->id = null;
        $this->publicUrl = null;
    }

    // Getters & Setters
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getTravelSpotId(): int { return $this->travelSpotId; }
    public function setTravelSpotId(int $travelSpotId): void { $this->travelSpotId = $travelSpotId; }

    public function getUrl(): string { return $this->url; }
    public function setUrl(string $url): void { $this->url = $url; }

    public function getPublicUrl(): ?string { return $this->publicUrl; }
    public function setPublicUrl(?string $publicUrl): void { $this->publicUrl = $publicUrl; }
}
