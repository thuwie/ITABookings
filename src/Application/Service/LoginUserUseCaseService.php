<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\LoginUserUseCasePort;
use App\Application\Port\Outbound\UserRepositoryPort;
use App\Application\Port\Outbound\DriverRepositoryPort;
use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;

class LoginUserUseCaseService implements LoginUserUseCasePort {
    private UserRepositoryPort $userRepositoryPort;
    private SessionManagerInterfacePort $sessionManagerPort;
    private ProviderRepositoryPort $providerRepositoryPort;
    private DriverRepositoryPort $driverRepositoryPort;

    public function __construct(
        UserRepositoryPort $userRepository,
        SessionManagerInterfacePort $sessionManager,
        ProviderRepositoryPort $providerRepositoryPort,
        DriverRepositoryPort $driverRepositoryPort
    ) {
        $this->userRepositoryPort = $userRepository;
        $this->sessionManagerPort = $sessionManager;
        $this->providerRepositoryPort = $providerRepositoryPort;
        $this->driverRepositoryPort = $driverRepositoryPort;
    }

    public function login(string $email, string $password) {
        $validationEmail = new Email($email);

        $user = $this->userRepositoryPort->findUserByEmail($validationEmail);

        if(!$user) {
             throw new \Exception("Email không tồn tại!!!");
        }

        $userId = $user->getId();

        $passwordHashStorage =$user->getPassword();

        $passwordObject = Password::fromHash($passwordHashStorage);

        if(!$passwordObject->verify($password)) {
             throw new \Exception("Mật khẩu không đúng !!!!!");
        };


        $sessionUser = $user->toSessionArray();

        $registeredAsProvider = $this->providerRepositoryPort->findUnVerifiedAccountByUserId($userId);
        $registeredAsDriver = $this->driverRepositoryPort->findUnVerifiedAccountByUserId($userId);

        if($registeredAsProvider) {
            $sessionUser = $user->toSessionArray() + [
                'temporary_role' => 'provider'];
        } ;

        if($registeredAsDriver) {
             $sessionUser = $user->toSessionArray() + [
                'temporary_role' => 'driver'];
        };

        $this->sessionManagerPort->set('user', $sessionUser);

        return  $sessionUser;
    }

}
