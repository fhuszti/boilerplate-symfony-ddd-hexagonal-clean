<?php

namespace App\Domain\User\Entity\ValueObject;

use App\Domain\Exception\DomainValidationException;

class EmailAddress
{
    private string $email;

    public function __construct(string $email)
    {
        $this->validate($email);
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    private function validate(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainValidationException('EmailAddress', 'Invalid email address format: '.$email);
        }
    }

    public function isEqual(EmailAddress $otherEmail): bool
    {
        return $this->email === $otherEmail->getEmail();
    }
}
