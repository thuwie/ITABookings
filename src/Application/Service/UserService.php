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

    public function createUser(
        string $firstName,
        string $lastName,
        string $plainPassword,
        string $email,
        ?string $phoneNumber = null,
        ?string $portrait = null,
        string $gender = 'male',
        ?string $dateOfBirth = null,
        ?string $cccd = null,
        ?string $address = null,
        ?int $provinceId = null,
        int $roleId = 4
    ): array {
        // Validations
        $emailVO = new Email($email);
        $passwordVO = Password::fromPlain($plainPassword);
        $phoneNumberVO = $phoneNumber ? new PhoneNumber($phoneNumber) : null;

        if ($this->userRepositoryPort->existsByEmail($emailVO)) {
            throw new \DomainException("Email already registered");
        }

        // Xử lý ngày sinh
        $dob = null;
        if ($dateOfBirth) {
            try {
                $dobObj = new \DateTimeImmutable($dateOfBirth);
                $dob = $dobObj->format('Y-m-d'); // chuyển thành string
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
            $gender,
            $dob,
            $cccd,
            $address,
            $provinceId,
            $roleId,
            $portrait
        );

        // save user
        $this->userRepositoryPort->save($user);

        return $user->toArray();
    }
}
