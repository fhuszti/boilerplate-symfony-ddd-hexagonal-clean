<?php

namespace App\Application\UseCase\CreateUser;

readonly class CreateUserCommand
{
    public function __construct(
        private string $username,
        private string $emailAddress,
        private string $password,
        private string $role
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
