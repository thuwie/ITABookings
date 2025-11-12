<?php
// src/Infrastructure/Adapters/Persistence/UserRepository.php
namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\UserRepositoryPort;
use App\Domain\Entity\User;
use App\Domain\ValueObject\Email;
use Illuminate\Database\Capsule\Manager as DB;

class UserRepository implements UserRepositoryPort {
    public function save(User $user) {
        $newUser = DB::table('users')->insert($user->toArray());
        return $newUser;
    }

    public function existsByEmail(Email $email): bool {
        return DB::table('users')->where('email', $email->value())->exists();
    }

    public function findById(int $id): ?User {
        $row = DB::table('users')->where('id', $id)->first();
        if (!$row) return null;

        return User::fromArray((array)$row);
    }
}
