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
    private string $gender;
    private ?\DateTimeImmutable $dateOfBirth;
    private ?string $cccd;
    private ?string $address;
    private ?int $provinceId;
    private int $roleId;
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
        string $gender = 'male',
        ?\DateTimeImmutable $dateOfBirth = null,
        ?string $cccd = null,
        ?string $address = null,
        ?int $provinceId = null,
        int $roleId = 4,
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
         $this->gender      = $gender;
        $this->dateOfBirth = $dateOfBirth;
        $this->cccd        = $cccd;
        $this->address     = $address;
        $this->provinceId  = $provinceId;
        $this->roleId      = $roleId;
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
    public function getGender(): string { return $this->gender; }
    public function getDateOfBirth(): ?\DateTimeImmutable { return $this->dateOfBirth; }
    public function getCCCD(): ?string { return $this->cccd; }
    public function getAddress(): ?string { return $this->address; }
    public function getProvinceId(): ?int { return $this->provinceId; }
    public function getRoleId(): int { return $this->roleId; }

    // ===== Domain Behaviors =====
    public function changePassword(string $newPassword): void {
        $this->password = $newPassword;
        $this->touch();
    }

    public function updateProfile(
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $phoneNumber = null,
        ?string $portrait = null,
        ?string $gender = null,
        ?\DateTimeImmutable $dateOfBirth = null,
        ?string $cccd = null,
        ?string $address = null,
        ?int $provinceId = null,
        ?int $roleId = null
    ): void {
        if ($firstName !== null) $this->firstName = $firstName;
        if ($lastName !== null) $this->lastName = $lastName;
        if ($phoneNumber !== null) $this->phoneNumber = $phoneNumber;
        if ($portrait !== null) $this->portrait = $portrait;
        if ($gender !== null) $this->gender = $gender;
        if ($dateOfBirth !== null) $this->dateOfBirth = $dateOfBirth;
        if ($cccd !== null) $this->cccd = $cccd;
        if ($address !== null) $this->address = $address;
        if ($provinceId !== null) $this->provinceId = $provinceId;
        if ($roleId !== null) $this->roleId = $roleId;
        $this->touch();
    }

    private function touch(): void {
        $this->updatedAt = new \DateTimeImmutable();
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
            'gender'       => $this->gender,
            'date_of_birth'=> $this->dateOfBirth?->format('Y-m-d'),
            'CCCD'         => $this->cccd,
            'address'      => $this->address,
            'province_id'  => $this->provinceId,
            'role_id'      => $this->roleId,
            'created_at'   => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? 0,
            $data['first_name'] ?? '',
            $data['last_name'] ?? '',
            $data['password'] ?? '',
            $data['email'] ?? '',
            $data['phone_number'] ?? null,
            $data['portrait'] ?? null,
            $data['gender'] ?? 'male',
            isset($data['date_of_birth']) ? new \DateTimeImmutable($data['date_of_birth']) : null,
            $data['CCCD'] ?? null,
            $data['address'] ?? null,
            $data['province_id'] ?? null,
            $data['role_id'] ?? 4,
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }
}
