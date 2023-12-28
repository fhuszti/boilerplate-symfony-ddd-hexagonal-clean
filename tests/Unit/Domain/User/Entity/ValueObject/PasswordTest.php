<?php

namespace App\Tests\Unit\Domain\User\Entity\ValueObject;

use App\Domain\Exception\DomainValidationException;
use App\Domain\User\Entity\ValueObject\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testCanBeInstantiatedWithValidPassword(): void
    {
        $passwordString = 'ValidPassword123';
        $password = new Password($passwordString);

        $this->assertInstanceOf(Password::class, $password);
        $this->assertEquals($passwordString, $password->getPassword());
    }

    public function testThrowsExceptionForExcessivelyLongPassword(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('Password must not be over 240 characters long');

        new Password(str_repeat('a', 241));
    }

    public function testIsEqualReturnsTrueForSamePassword(): void
    {
        $password1 = new Password('MySecurePassword');
        $password2 = new Password('MySecurePassword');

        $this->assertTrue($password1->isEqual($password2));
    }

    public function testIsEqualReturnsFalseForDifferentPasswords(): void
    {
        $password1 = new Password('MySecurePassword');
        $password2 = new Password('DifferentPassword');

        $this->assertFalse($password1->isEqual($password2));
    }
}
