<?php

namespace App\Domain\User\Entity\ValueObject;

use App\Domain\Exception\DomainValidationException;

class Username
{
    private string $username;

    public function __construct(string $username)
    {
        $this->validate($username);
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    private function validate(string $username): void
    {
        if (strlen($username) < 3 || strlen($username) > 30) {
            throw new DomainValidationException('Username', 'Username must be between 3 and 30 characters: '.$username);
        }
    }

    public function isEqual(Username $otherUsername): bool
    {
        return $this->username === $otherUsername->getUsername();
    }
}
