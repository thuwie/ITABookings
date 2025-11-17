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
            'created_at'   => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

     public function toInsertArray(): array {
        return [
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
            'created_at'   => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

   public static function fromArray(array $data): User {
        return new User(
            $data['id'],
            $data['first_name'],
            $data['last_name'],
            $data['password'],
            $data['email'],
            $data['phone_number'],
            $data['portrait'] ?? '',
            $data['gender'],
            isset($data['date_of_birth']) ? new \DateTimeImmutable($data['date_of_birth']) : null,
            $data['CCCD'] ?? '',
            $data['address'] ?? '',
            $data['province_id'] ?? null,
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }
}
