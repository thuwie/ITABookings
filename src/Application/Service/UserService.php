<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\UserServicePort;
use App\Domain\Entity\User;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Application\Port\Outbound\UserRepositoryPort;
use App\Domain\ValueObject\PhoneNumber;

class UserService implements UserServicePort {
       private UserRepositoryPort $userRepositoryPort;

    public function __construct(UserRepositoryPort $userRepository) {
        $this->userRepositoryPort = $userRepository;
    }

    public function createUser($firstName, $lastName, $plainPassword, $email, $phoneNumber, $portrait):array {
        //valid use
         $emailVO = new Email($email);
         $passwordVO = Password::fromPlain($plainPassword);
         $phoneNumberVO = new PhoneNumber($phoneNumber);

         if($this->userRepositoryPort->existsByEmail($emailVO)) {
             throw new \DomainException("Email already registered");
         }

         //init user
        $user = new User(
            0, 
            $firstName,
            $lastName,
            $passwordVO->getHash(),
            $emailVO->value(),
            $phoneNumber,
            $portrait
        );

        //save user
        $this->userRepositoryPort->save($user);
        return $user->toArray();
    }
}
