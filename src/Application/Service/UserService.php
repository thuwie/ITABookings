<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\UserServicePort;
use App\Domain\Entity\User;

class UserService implements UserServicePort {
    public function createUser(string $name): array {
        // domain logic đơn giản
        // $user = new User(uniqid(), $name); // <-- gọi vào domain
          return [
            'id' => uniqid(),
            'name' => $name
        ];
    }
}
