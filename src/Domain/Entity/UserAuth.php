<?php 
namespace App\Domain\Entity;

class UserAuth
{
    public int $id;
    public string $email;
    public string $passwordHash;

    public function __construct(int $id, string $email, string $passwordHash)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function getPassword() {
        return $this->passwordHash;
    }
}
