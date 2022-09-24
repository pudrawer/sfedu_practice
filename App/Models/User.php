<?php

namespace App\Models;

class User
{
    private $id;
    private $email;
    private $password;
    private $name;
    private $surname;
    private $phone;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return 'User';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email ?? '';
    }

    public function setPassword(string $pass): self
    {
        $this->password = $pass;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname ?? '';
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone ?? '';
    }
}
