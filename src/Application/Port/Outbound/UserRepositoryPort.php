<?php
// src/Application/Ports/Outbound/UserRepositoryPort.php
namespace App\Application\Port\Outbound;

use App\Domain\Entity\User;
use App\Domain\ValueObject\Email;
use App\Domain\Entity\UserAuth;
use App\Domain\Entity\UserRole; 

interface UserRepositoryPort {
    public function save(User $user);
    public function existsByEmail(Email $email): bool;
    public function findById(int $id): ?User;
    public function findUserByEmail(Email $email):?UserAuth;
    public function saveRole(UserRole $role);
}
