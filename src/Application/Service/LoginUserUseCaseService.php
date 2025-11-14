<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\LoginUserUseCasePort;
use App\Application\Port\Outbound\UserRepositoryPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;

class LoginUserUseCaseService implements LoginUserUseCasePort {
    private UserRepositoryPort $userRepositoryPort;
    private SessionManagerInterfacePort $sessionManagerPort;


    public function __construct(
        UserRepositoryPort $userRepository,
        SessionManagerInterfacePort $sessionManager
    ) {
        $this->userRepositoryPort = $userRepository;
        $this->sessionManagerPort = $sessionManager;
    }

    public function login(string $email, string $password) {
        $validationEmail = new Email($email);

        $user = $this->userRepositoryPort->findUserByEmail($validationEmail);

        if(!$user) {
             throw new \Exception("Email not found");
        }

        $passwordHashStorage =$user->getPassword();

        $passwordObject = Password::fromHash($passwordHashStorage);

        if(!$passwordObject->verify($password)) {
             throw new \Exception("Password is not correct");
        };

        $this->sessionManagerPort->set('user_id', $user->id);

        return  $user;
    }

}
