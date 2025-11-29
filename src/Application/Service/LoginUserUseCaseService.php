<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\LoginUserUseCasePort;
use App\Application\Port\Outbound\UserRepositoryPort;
use App\Application\Port\Outbound\DriverRepositoryPort;
use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Application\Port\Outbound\AdminRepositoryPort;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;

class LoginUserUseCaseService implements LoginUserUseCasePort {
    private UserRepositoryPort $userRepositoryPort;
    private SessionManagerInterfacePort $sessionManagerPort;
    private ProviderRepositoryPort $providerRepositoryPort;
    private DriverRepositoryPort $driverRepositoryPort;
    private AdminRepositoryPort $adminRepository;

    public function __construct(
        UserRepositoryPort $userRepository,
        SessionManagerInterfacePort $sessionManager,
        ProviderRepositoryPort $providerRepositoryPort,
        DriverRepositoryPort $driverRepositoryPort,
        AdminRepositoryPort $adminRepository
    ) {
        $this->userRepositoryPort = $userRepository;
        $this->sessionManagerPort = $sessionManager;
        $this->providerRepositoryPort = $providerRepositoryPort;
        $this->driverRepositoryPort = $driverRepositoryPort;
        $this->adminRepository = $adminRepository;
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

        $registeredAsProvider = $this->providerRepositoryPort->findByUserIdWithVerifyFallback($userId);
        $registeredAsDriver = $this->driverRepositoryPort->findByUserIdWithVerifyFallback($userId);
        $isAdminLogin = $this->adminRepository->isAdminLogin($userId);

       // ===== PROVIDER =====
    if ($registeredAsProvider !== null) {
        if (!$registeredAsProvider->getVerifiedAt()) {
            $sessionUser = $user->toSessionArray() + [
                'role' => 'provider',
                'status' => 'unverified'
            ];
        } else {
            $sessionUser = $user->toSessionArray() + [
                'role' => 'provider',
                'status' => 'verified'
            ];
        }
    }

    // ===== DRIVER =====
    if ($registeredAsDriver !== null) {
        if (!$registeredAsDriver->getVerifiedAt()) {
            $sessionUser = $user->toSessionArray() + [
                'role' => 'driver',
                'status' => 'unverified'
            ];
        } else {
            $sessionUser = $user->toSessionArray() + [
                'role' => 'driver',
                'status' => 'verified'
            ];
        }
    }

    // ============ ADMIN =================
         if($isAdminLogin) {
             $sessionUser = $user->toSessionArray() + [
                'role' => 'admin',
            ];
         }

        $this->sessionManagerPort->set('user', $sessionUser);

        return  $sessionUser;
    }

}
