<?php
// src/Domain/ValueObject/Email.php
namespace App\Domain\ValueObject;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format: {$value}");
        }
        $this->value = strtolower($value); // chuẩn hóa email
    }

   // Phải có method này
    public function value(): string
    {
        return $this->value;
    }

    // Có thể thêm toString cho tiện
    public function __toString(): string
    {
        return $this->value;
    }
}
