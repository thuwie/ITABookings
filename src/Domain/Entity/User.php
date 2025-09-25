<?php
namespace App\Domain\Entity;

class User {
    private string $id;
    private string $name;

    public function __construct(string $id, string $name) {
        if (empty($name)) {
            throw new \InvalidArgumentException("Name cannot be empty");
        }
        $this->id = $id;
        $this->name = $name;
    }

    public function rename(string $newName): void {
        if (empty($newName)) {
            throw new \InvalidArgumentException("New name cannot be empty");
        }
        $this->name = $newName;
    }

    public function toArray(): array {
        return ['id' => $this->id, 'name' => $this->name];
    }
}
