<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\UserServicePort;
use App\Domain\Entity\User;
use App\Domain\Entity\UserRole;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Application\Port\Outbound\UserRepositoryPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Domain\ValueObject\PhoneNumber;
use Exception;

class UserService implements UserServicePort {
       private UserRepositoryPort $userRepositoryPort;
       private SessionManagerInterfacePort $sessionManager;

    public function __construct(UserRepositoryPort $userRepository, SessionManagerInterfacePort $sessionManager
    ) {
        $this->userRepositoryPort = $userRepository;
        $this->sessionManager = $sessionManager;
    }

    public function createUser($user): array {
        $firstName = $user['first_name'];
        $lastName = $user['last_name'];
        $gender = $user['gender'];
        $cccd = $user['cccd'];
        $address = $user['address'];
        $provinceId = $user['province_id'];
        $portrait = '';
        $email = $user['email'];
        $password = $user['password'];
        $phoneNumber = $user['phone_number'];
        $dateOfBirth = $user['date_of_birth'];

        // Validations
        $emailVO = new Email($email);
        $passwordVO = Password::fromPlain($password);
        $phoneNumberVO = $phoneNumber ? new PhoneNumber($phoneNumber) : null;

        if ($this->userRepositoryPort->existsByEmail($emailVO)) {
            throw new \DomainException("Email already registered");
        }

        // Xử lý ngày sinh
        $dob = null;
        if ($dateOfBirth) {
            try {
                $dob = new \DateTimeImmutable($dateOfBirth);
            } catch (\Exception $e) {
                throw new \DomainException("Ngày sinh không hợp lệ");
            }
        }

        // init user
        $user = new User(
            0,
            $firstName,
            $lastName,
            $passwordVO->getHash(),
            $emailVO->value(),
            $phoneNumberVO ? $phoneNumberVO->getValue() : null, // PHP <8 dùng cách này
            $portrait,
            $gender,
            $dob,
            $cccd,
            $address,
            $provinceId,
        );

        // save user
        $newUser = $this->userRepositoryPort->save($user);

        $userId = $newUser->id;
        $role = new  UserRole ($userId, 4);
        $role_user = $this->userRepositoryPort->saveRole($role);

        return $role_user
        ? ['status' => 'success', 'message' => 'Đăng ký tài khoản thành công']
        : ['status' => 'failed', 'message' => 'Đăng ký tài khoản thất bại'];
    }

    public function getUserInformation(): array {
        $userSession = $this->sessionManager->get('user');
        $userId = $userSession['id'];
        $isUserFound = $this->userRepositoryPort->findById($userId);
        if(!$isUserFound) {
             throw new \Exception("Not found user");
        }

        return $isUserFound->toArray();
    }
    public function getUsersById($ids): array {
        $users = $this->userRepositoryPort->getUsersById($ids);
        return $users;
    }
    public function getUserById($id): array {
        $user= $this->userRepositoryPort->findById($id);
        $arrayUser = ['full_name' => $user->getLastName() . "" . $user->getFirstName(), 'phone' => $user->getPhoneNumber(),
        'email' => $user->getEmail()];

        return $arrayUser;
    }
}
