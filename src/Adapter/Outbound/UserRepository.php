<?php
// src/Infrastructure/Adapters/Persistence/UserRepository.php
namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\UserRepositoryPort;
use App\Domain\Entity\User;
use App\Domain\Entity\UserRole;
use App\Domain\ValueObject\Email;
use Illuminate\Database\Capsule\Manager as DB;
use App\Domain\Entity\UserAuth; 
class UserRepository implements UserRepositoryPort {
    public function save(User $user) {
        $id = DB::table('users')->insertGetId($user->toInsertArray());
        $data = DB::table('users')->find($id);
        return  $data;
    }

    public function existsByEmail(Email $email): bool {
        return DB::table('users')->where('email', $email->value())->exists();
    }

    public function findById(int $id): ?User {
        $row = DB::table('users')->where('id', $id)->first();
        if (!$row) return null;

        return User::fromArray((array)$row);
    }

    public function findUserByEmail(Email $email):?UserAuth
    {
        $row = DB::table('users')
            ->select(['id', 'email', 'password', 'first_name', 'last_name', 'portrait', 'gender'])
            ->where('email', $email->value())
            ->first();

        if (!$row) {
            return null;
        }

        return new UserAuth(
            $row->id,
            $row->email,
            $row->password,
            $row->first_name,
            $row->last_name,
            $row->portrait,
            $row->gender
        ); 
    }

    public function saveRole(UserRole $role): UserRole {
        DB::table('user_roles')->insert($role->toArray());
        return $role;
    }

     public function getUsersById($ids): array {
        $users = DB::table('users')
            ->whereIn('id', $ids)
            ->get();
        return $users->toArray();
     }

}
