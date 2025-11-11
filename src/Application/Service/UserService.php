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

    public function createUser($user): array {
        $firstName = $user['first_name'];
        $lastName = $user['last_name'];
        $gender = $user['gender'];
        $cccd = $user['cccd'];
        $address = $user['address'];
        $provinceId = $user['province_id'];
        $portrait = '';
        $roleId = $user['role_id'];
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
            $roleId,
        );

        // save user
        $result = $this->userRepositoryPort->save($user);

        return $result
        ? ['status' => 'success', 'message' => 'Province and images saved successfully']
        : ['status' => 'failed', 'message' => 'Province and images saved unsuccessfully'];
    }
}
