<?php
namespace App\Domain\Entity;

class UserRole
{
    private int $userId;
    private int $roleId;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $userId,
        int $roleId,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->userId = $userId;
        $this->roleId = $roleId;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    // ===== Getters =====
    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // ===== Domain Behaviors =====
    public function updateRole(int $roleId): void
    {
        $this->roleId = $roleId;
        $this->touch();
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // ===== For return to Application Layer =====
    public function toArray(): array
    {
        return [
            'user_id'    => $this->userId,
            'role_id'    => $this->roleId,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['user_id'],
            $data['role_id'],
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }
}
