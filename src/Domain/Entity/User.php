<?php
namespace App\Domain\Entity;

class User 
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $password;
    private string $email;
    private ?string $phoneNumber;
    private ?string $portrait;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $password,
        string $email,
        ?string $phoneNumber = null,
        ?string $portrait = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->firstName   = $firstName;
        $this->lastName    = $lastName;
        $this->password    = $password;
        $this->email       = $email;
        $this->phoneNumber = $phoneNumber;
        $this->portrait    = $portrait;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    // ===== Getters =====
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getPassword(): string { return $this->password; }
    public function getEmail(): string { return $this->email; }
    public function getPhoneNumber(): ?string { return $this->phoneNumber; }
    public function getPortrait(): ?string { return $this->portrait; }

    // ===== Domain Behaviors =====
    public function changePassword(string $newPassword): void {
        $this->password = $newPassword;
        $this->touch();
    }

    public function updateProfile(
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $phoneNumber = null,
        ?string $portrait = null
    ): void {
        if ($firstName !== null) $this->firstName = $firstName;
        if ($lastName !== null) $this->lastName = $lastName;
        if ($phoneNumber !== null) $this->phoneNumber = $phoneNumber;
        if ($portrait !== null) $this->portrait = $portrait;
        $this->touch();
    }


    // ===== For return to Application Layer =====
    public function toArray(): array {
         return [
        'id'           => $this->id,
        'first_name'   => $this->firstName,
        'last_name'    => $this->lastName,
        'email'        => $this->email,         // string
        'password'     => $this->password,      // string hash
        'phone_number' => $this->phoneNumber,
        'portrait'     => $this->portrait,
        'created_at'   => $this->createdAt->format('Y-m-d H:i:s'),
        'updated_at'   => $this->updatedAt->format('Y-m-d H:i:s'),
    ];
    }
}
