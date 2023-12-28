<?php

namespace App\Tests\Unit\Domain\User\Entity\ValueObject;

use App\Domain\Exception\DomainValidationException;
use App\Domain\User\Entity\ValueObject\Username;
use PHPUnit\Framework\TestCase;

class UsernameTest extends TestCase
{
    public function testCanBeInstantiatedWithValidUsername(): void
    {
        $usernameString = 'ValidUsername';
        $username = new Username($usernameString);

        $this->assertInstanceOf(Username::class, $username);
        $this->assertEquals($usernameString, $username->getUsername());
    }

    public function testThrowsExceptionForTooShortUsername(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('Username must be between 3 and 30 characters');

        new Username('ab');
    }

    public function testThrowsExceptionForTooLongUsername(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('Username must be between 3 and 30 characters');

        new Username(str_repeat('a', 31));
    }

    public function testIsEqualReturnsTrueForSameUsername(): void
    {
        $username1 = new Username('MyUsername');
        $username2 = new Username('MyUsername');

        $this->assertTrue($username1->isEqual($username2));
    }

    public function testIsEqualReturnsFalseForDifferentUsernames(): void
    {
        $username1 = new Username('MyUsername');
        $username2 = new Username('OtherUsername');

        $this->assertFalse($username1->isEqual($username2));
    }
}
