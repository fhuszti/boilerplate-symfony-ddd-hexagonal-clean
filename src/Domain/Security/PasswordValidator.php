<?php

namespace App\Domain\Security;

use App\Domain\Exception\DomainValidationException;

class PasswordValidator
{
    public static function validate(string $password): void
    {
        if (strlen($password) < 8 || strlen($password) > 50) {
            throw new DomainValidationException('Password', 'Password must be between 8 and 50 characters');
        }
    }
}
