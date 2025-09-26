<?php
// src/Domain/ValueObject/PhoneNumber.php
namespace App\Domain\ValueObject;

class PhoneNumber
{
    private string $value;

    public function __construct(string $value)
    {
        // kiểm tra chỉ chứa số
        if (!ctype_digit($value)) {
            throw new \InvalidArgumentException("Phone number must contain only digits.");
        }

        // kiểm tra độ dài = 10
        if (strlen($value) !== 10) {
            throw new \InvalidArgumentException("Phone number must be exactly 10 digits.");
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
