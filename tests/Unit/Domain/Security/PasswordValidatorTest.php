<?php

namespace App\Tests\Unit\Domain\Security;

use App\Domain\Exception\DomainValidationException;
use App\Domain\Security\PasswordValidator;
use PHPUnit\Framework\TestCase;

class PasswordValidatorTest extends TestCase
{
    public function testValidateThrowsExceptionForShortPassword(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('Password must be between 8 and 50 characters');

        PasswordValidator::validate('short');
    }

    public function testValidateThrowsExceptionForLongPassword(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('Password must be between 8 and 50 characters');

        PasswordValidator::validate(str_repeat('a', 51));
    }

    public function testValidateSuccessForValidPassword(): void
    {
        $validPassword = 'ValidPass123';

        try {
            PasswordValidator::validate($validPassword);
            $this->assertTrue(true);
        } catch (DomainValidationException $e) {
            $this->fail('No exception should be thrown for a valid password');
        }
    }
}
