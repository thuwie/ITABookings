<?php
// src/Application/Ports/Outbound/UserRepositoryPort.php
namespace App\Application\Port\Outbound;

use App\Domain\Entity\User;
use App\Domain\ValueObject\Email;

interface UserRepositoryPort {
    public function save(User $user): void;
    public function existsByEmail(Email $email): bool;
    public function findById(int $id): ?User;
}
