<?php

namespace App\Domain\User\Entity\ValueObject;

use App\Domain\Exception\DomainValidationException;

class Password
{
    private string $password;

    public function __construct(string $password)
    {
        $this->validate($password);
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    private function validate(string $password): void
    {
        if (strlen($password) > 240) {
            throw new DomainValidationException('Password', 'Password must not be over 240 characters long: '.$password);
        }
    }

    public function isEqual(Password $otherPassword): bool
    {
        return $this->password === $otherPassword->getPassword();
    }
}
