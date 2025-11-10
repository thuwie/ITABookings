<?php
// src/Application/Ports/UserServicePort.php
namespace App\Application\Port\Inbound;

interface UserServicePort {
    public function createUser(
        string $firstName,
        string $lastName,
        string $plainPassword,
        string $email,
        ?string $phoneNumber = null,
        ?string $portrait = null,
        string $gender = 'male',
        ?string $dateOfBirth = null,
        ?string $cccd = null,
        ?string $address = null,
        ?int $provinceId = null,
        int $roleId = 4
    ): array;
}