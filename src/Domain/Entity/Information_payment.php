<?php
namespace App\Domain\Entity;

class InformationPayment
{
    private int $id;
    private int $userId;
    private string $fullName;
    private string $accountNumber;
    private string $bankName;
    private ?string $qrCode;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        int $userId,
        string $fullName,
        string $accountNumber,
        string $bankName,
        ?string $qrCode = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->fullName = $fullName;
        $this->accountNumber = $accountNumber;
        $this->bankName = $bankName;
        $this->qrCode = $qrCode;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    // ===== Getters =====
    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getFullName(): string { return $this->fullName; }
    public function getAccountNumber(): string { return $this->accountNumber; }
    public function getBankName(): string { return $this->bankName; }
    public function getQrCode(): ?string { return $this->qrCode; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    // ===== Setters =====
    public function setUserId(int $userId): void { $this->userId = $userId; $this->touch(); }
    public function setFullName(string $fullName): void { $this->fullName = $fullName; $this->touch(); }
    public function setAccountNumber(string $accountNumber): void { $this->accountNumber = $accountNumber; $this->touch(); }
    public function setBankName(string $bankName): void { $this->bankName = $bankName; $this->touch(); }
    public function setQrCode(?string $qrCode): void { $this->qrCode = $qrCode; $this->touch(); }

    private function touch(): void { $this->updatedAt = new \DateTimeImmutable(); }

    // ===== For return to Application Layer =====
    public function toArray(): array {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'full_name' => $this->fullName,
            'account_number' => $this->accountNumber,
            'bank_name' => $this->bankName,
            'qr_code' => $this->qrCode,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? 0,
            $data['user_id'] ?? 0,
            $data['full_name'] ?? '',
            $data['account_number'] ?? '',
            $data['bank_name'] ?? '',
            $data['qr_code'] ?? null,
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
