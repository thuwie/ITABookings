<?php
namespace App\Domain\Entity;

class Province
{
    private int $id;
    private string $code;
    private string $name;
    private string $type;
    private string $type_display;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    // --- Constructor ---
    public function __construct(
        int $id,
        string $code,
        string $name,
        string $type,
        string $type_display,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->type = $type;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->type_display = $type_display;
    }

    // --- Getters ---
    public function getId(): int { return $this->id; }
    public function getCode(): string { return $this->code; }
    public function getName(): string { return $this->name; }
    public function getType(): string { return $this->type; }
     public function getTypeDisplay(): string { return $this->type_display; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }

    // --- Setters ---
    public function setCode(string $code): void { $this->code = $code; }
    public function setName(string $name): void { $this->name = $name; }
    public function setType(string $type): void { $this->type = $type; }
    public function setTypeDisplay(string $typeDisplay): void { $this->type_display = $typeDisplay; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void { $this->updatedAt = $updatedAt; }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'code'       => $this->code,
            'name'       => $this->name,
            'type'       => $this->type,
            'type_display' => $this->type_display,
            'created_at' => $this->createdAt ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ];
    }
}