<?php
namespace App\Domain\Entity;

class Provider
{
    private int $id;
    private int $userId;
    private string $name;
    private ?string $logoUrl;
    private ?string $description;
    private string $email;
    private ?string $phoneNumber;
    private ?string $address;
    private ?int $provinceId;
    private ?float $averageRates;
    private int $ratingCount;
    private ?\DateTimeImmutable $verifiedAt;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        int $userId,
        string $name,
        ?string $logoUrl = null,
        ?string $description = null,
        string $email,
        ?string $phoneNumber = null,
        ?string $address = null,
        ?int $provinceId = null,
        ?float $averageRates = null,
        int $ratingCount = 0,
        ?\DateTimeImmutable $verifiedAt = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->logoUrl = $logoUrl;
        $this->description = $description;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->provinceId = $provinceId;
        $this->averageRates = $averageRates;
        $this->ratingCount = $ratingCount;
        $this->verifiedAt = $verifiedAt;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    // ===== Setters =====
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
        $this->touch();
    }

    public function setName(string $name): void
    {
        $this->name = $name;
        $this->touch();
    }

    public function setLogoUrl(?string $logoUrl): void
    {
        $this->logoUrl = $logoUrl;
        $this->touch();
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
        $this->touch();
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
        $this->touch();
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
        $this->touch();
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
        $this->touch();
    }

    public function setProvinceId(?int $provinceId): void
    {
        $this->provinceId = $provinceId;
        $this->touch();
    }

    public function setAverageRates(?float $averageRates): void
    {
        $this->averageRates = $averageRates;
        $this->touch();
    }

    public function setRatingCount(int $ratingCount): void
    {
        $this->ratingCount = $ratingCount;
        $this->touch();
    }

    public function setVerifiedAt(?\DateTimeImmutable $verifiedAt): void
    {
        $this->verifiedAt = $verifiedAt;
        $this->touch();
    }

    // ===== Getters =====
    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getName(): string { return $this->name; }
    public function getLogoUrl(): ?string { return $this->logoUrl; }
    public function getDescription(): ?string { return $this->description; }
    public function getEmail(): string { return $this->email; }
    public function getPhoneNumber(): ?string { return $this->phoneNumber; }
    public function getAddress(): ?string { return $this->address; }
    public function getProvinceId(): ?int { return $this->provinceId; }
    public function getAverageRates(): ?float { return $this->averageRates; }
    public function getRatingCount(): int { return $this->ratingCount; }
    public function getVerifiedAt(): ?\DateTimeImmutable { return $this->verifiedAt; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    // ===== Domain Behaviors =====
    public function updateProfile(
        ?string $name = null,
        ?string $logoUrl = null,
        ?string $description = null,
        ?string $email = null,
        ?string $phoneNumber = null,
        ?string $address = null,
        ?int $provinceId = null,
        ?float $averageRates = null,
        ?int $ratingCount = null,
        ?\DateTimeImmutable $verifiedAt = null
    ): void {
        if ($name !== null) $this->name = $name;
        if ($logoUrl !== null) $this->logoUrl = $logoUrl;
        if ($description !== null) $this->description = $description;
        if ($email !== null) $this->email = $email;
        if ($phoneNumber !== null) $this->phoneNumber = $phoneNumber;
        if ($address !== null) $this->address = $address;
        if ($provinceId !== null) $this->provinceId = $provinceId;
        if ($averageRates !== null) $this->averageRates = $averageRates;
        if ($ratingCount !== null) $this->ratingCount = $ratingCount;
        if ($verifiedAt !== null) $this->verifiedAt = $verifiedAt;
        $this->touch();
    }

    private function touch(): void {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // ===== For return to Application Layer =====
    public function toArray(): array {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'name' => $this->name,
            'logo_url' => $this->logoUrl,
            'description' => $this->description,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber,
            'address' => $this->address,
            'province_id' => $this->provinceId,
            'average_rates' => $this->averageRates,
            'rating_count' => $this->ratingCount,
            'verified_at' => $this->verifiedAt?->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? 0,
            $data['user_id'] ?? 0,
            $data['name'] ?? '',
            $data['logo_url'] ?? null,
            $data['description'] ?? null,
            $data['email'] ?? '',
            $data['phone_number'] ?? null,
            $data['address'] ?? null,
            $data['province_id'] ?? null,
            $data['average_rates'] ?? null,
            $data['rating_count'] ?? 0,
            isset($data['verified_at']) ? new \DateTimeImmutable($data['verified_at']) : null,
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }

    public function toInsertArray(): array {
        $arr = $this->toArray();
        unset($arr['id']); // id auto-increment
        return $arr;
    }
}
