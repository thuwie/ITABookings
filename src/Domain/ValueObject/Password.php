<?php
// src/Domain/ValueObject/Password.php
namespace App\Domain\ValueObject;

class Password
{
    private string $hash;

    private function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    // Tạo password từ plain text (có validate độ mạnh)
    public static function fromPlain(string $plain): self
    {
        if (strlen($plain) < 8) {
            throw new \InvalidArgumentException("Password must be at least 8 characters.");
        }
        if (!preg_match('/[A-Z]/', $plain) || !preg_match('/[0-9]/', $plain)) {
            throw new \InvalidArgumentException("Password must contain at least one uppercase letter and one number.");
        }

        $hash = password_hash($plain, PASSWORD_BCRYPT);
        return new self($hash);
    }

    // Tạo từ hash có sẵn (khi load từ DB)
    public static function fromHash(string $hash): self
    {
        return new self($hash);
    }

    public function verify(string $plain): bool
    {
        return password_verify($plain, $this->hash);
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}
