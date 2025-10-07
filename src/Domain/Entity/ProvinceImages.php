<?php
namespace App\Domain\Entity;

class ProvinceImages
{
    private ?int $id;
    private int $provinceId;
    private string $url;
    private string $publicUrl;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        ?int $id,
        int $provinceId,
        string $url,
        string $publicUrl,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->provinceId = $provinceId;
        $this->url = $url;
        $this->publicUrl = $publicUrl;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // --- Getters ---
    public function getId(): ?int { return $this->id; }
    public function getProvinceId(): int { return $this->provinceId; }
    public function getUrl(): string { return $this->url; }
    public function getPublicUrl(): string { return $this->publicUrl; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }

    // --- Setters ---
    public function setProvinceId(int $provinceId): void { $this->provinceId = $provinceId; }
    public function setUrl(string $url): void { $this->url = $url; }
    public function setPublicUrl(string $publicUrl): void { $this->publicUrl = $publicUrl; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void { $this->updatedAt = $updatedAt; }

    // --- Chuyển entity thành mảng để lưu vào DB ---
    public function toArray(): array
    {
        return [
            'province_id' => $this->provinceId,
            'url'         => $this->url,
            'publicUrl'   => $this->publicUrl,
            'created_at'  => $this->createdAt?->format('Y-m-d H:i:s') ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updatedAt?->format('Y-m-d H:i:s') ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ];
    }
}   