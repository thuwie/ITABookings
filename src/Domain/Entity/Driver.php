<?php
namespace App\Domain\Entity;

class Driver
{
    private int $id;
    private int $userId;
    private int $providerId;
    private string $licenseNumber;
    private string $licenseClass;
    private \DateTimeImmutable $licenseIssueDate;
    private \DateTimeImmutable $licenseExpiryDate;
    private string $status; // valid | expired | suspended | revoked
    private int $experience;
    private ?float $averageRates;
    private int $ratingCount;
    private ?\DateTimeImmutable $verifiedAt;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        int $userId,
        int $providerId,
        string $licenseNumber,
        string $licenseClass,
        \DateTimeImmutable $licenseIssueDate,
        \DateTimeImmutable $licenseExpiryDate,
        string $status = 'valid',
        int $experience = 0,
        ?float $averageRates = null,
        int $ratingCount = 0,
        ?\DateTimeImmutable $verifiedAt = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->providerId = $providerId;
        $this->licenseNumber = $licenseNumber;
        $this->licenseClass = $licenseClass;
        $this->licenseIssueDate = $licenseIssueDate;
        $this->licenseExpiryDate = $licenseExpiryDate;
        $this->status = $status;
        $this->experience = $experience;
        $this->averageRates = $averageRates;
        $this->ratingCount = $ratingCount;
        $this->verifiedAt = $verifiedAt;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    // ===== Setters =====
    public function setProviderId(int $providerId): void
    {
        $this->providerId = $providerId;
        $this->touch();
    }

    public function setLicenseNumber(string $licenseNumber): void
    {
        $this->licenseNumber = $licenseNumber;
        $this->touch();
    }

    public function setLicenseClass(string $licenseClass): void
    {
        $this->licenseClass = $licenseClass;
        $this->touch();
    }

    public function setLicenseIssueDate(\DateTimeImmutable $date): void
    {
        $this->licenseIssueDate = $date;
        $this->touch();
    }

    public function setLicenseExpiryDate(\DateTimeImmutable $date): void
    {
        $this->licenseExpiryDate = $date;
        $this->touch();
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->touch();
    }

    public function setExperience(int $experience): void
    {
        $this->experience = $experience;
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
    public function getProviderId(): int { return $this->providerId; }
    public function getLicenseNumber(): string { return $this->licenseNumber; }
    public function getLicenseClass(): string { return $this->licenseClass; }
    public function getLicenseIssueDate(): \DateTimeImmutable { return $this->licenseIssueDate; }
    public function getLicenseExpiryDate(): \DateTimeImmutable { return $this->licenseExpiryDate; }
    public function getStatus(): string { return $this->status; }
    public function getExperience(): int { return $this->experience; }
    public function getAverageRates(): ?float { return $this->averageRates; }
    public function getRatingCount(): int { return $this->ratingCount; }
    public function getVerifiedAt(): ?\DateTimeImmutable { return $this->verifiedAt; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    // ===== Domain Behaviors =====
    public function updateDriverInfo(
        ?int $providerId = null,
        ?string $licenseNumber = null,
        ?string $licenseClass = null,
        ?\DateTimeImmutable $licenseIssueDate = null,
        ?\DateTimeImmutable $licenseExpiryDate = null,
        ?string $status = null,
        ?int $experience = null,
        ?float $averageRates = null,
        ?int $ratingCount = null,
        ?\DateTimeImmutable $verifiedAt = null
    ): void {
        if ($providerId !== null) $this->providerId = $providerId;
        if ($licenseNumber !== null) $this->licenseNumber = $licenseNumber;
        if ($licenseClass !== null) $this->licenseClass = $licenseClass;
        if ($licenseIssueDate !== null) $this->licenseIssueDate = $licenseIssueDate;
        if ($licenseExpiryDate !== null) $this->licenseExpiryDate = $licenseExpiryDate;
        if ($status !== null) $this->status = $status;
        if ($experience !== null) $this->experience = $experience;
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
            'provider_id' => $this->providerId,
            'license_number' => $this->licenseNumber,
            'license_class' => $this->licenseClass,
            'license_issue_date' => $this->licenseIssueDate->format('Y-m-d'),
            'license_expiry_date' => $this->licenseExpiryDate->format('Y-m-d'),
            'status' => $this->status,
            'experience' => $this->experience,
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
            $data['provider_id'] ?? null,
            $data['license_number'] ?? '',
            $data['license_class'] ?? '',
            new \DateTimeImmutable($data['license_issue_date']),
            new \DateTimeImmutable($data['license_expiry_date']),
            $data['status'] ?? 'valid',
            $data['experience'] ?? 0,
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
