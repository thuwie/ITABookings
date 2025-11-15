<?php 
namespace App\Domain\Entity;

class UserAuth
{
    public int $id;
    public string $email;
    public string $passwordHash;
    public string $first_name;
    public string $last_name;
    public string $portrait;
    public string $gender;

    public function __construct(int $id, string $email, string $passwordHash, string $first_name, string $last_name, 
    string $portrait, string $gender)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->portrait = $portrait;
        $this->gender = $gender;
    }

    public function getPassword() {
        return $this->passwordHash;
    }

    public function toSessionArray(): array
    {
        return [
            'id'         => $this->id,
            'email'      => $this->email,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'portrait'   => $this->portrait,
            'gender' => $this->gender
        ];
    }
}
