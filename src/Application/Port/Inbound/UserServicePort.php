<?php
// src/Application/Ports/UserServicePort.php
namespace App\Application\Port\Inbound;

interface UserServicePort {
    public function createUser($user): array;
    public function getUserInformation(): array;
    public function getUsersById($ids): array;
}