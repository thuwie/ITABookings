<?php
// src/Application/Ports/UserServicePort.php
namespace App\Application\Port\Inbound;

interface UserServicePort {
    public function createUser($firstName, $lastName, $plainPassword, $email, $phoneNumber, $portrait): array;
}
