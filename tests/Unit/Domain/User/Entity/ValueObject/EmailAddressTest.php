<?php

namespace App\Tests\Unit\Domain\User\Entity\ValueObject;

use App\Domain\Exception\DomainValidationException;
use App\Domain\User\Entity\ValueObject\EmailAddress;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{
    public function testCanBeInstantiatedWithValidEmail(): void
    {
        $email = new EmailAddress('test@example.com');
        $this->assertInstanceOf(EmailAddress::class, $email);
        $this->assertEquals('test@example.com', $email->getEmail());
    }

    public function testThrowsExceptionForInvalidEmailFormat(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('Invalid email address format: not-an-email');

        new EmailAddress('not-an-email');
    }

    public function testIsEqualReturnsTrueForSameEmail(): void
    {
        $email1 = new EmailAddress('test@example.com');
        $email2 = new EmailAddress('test@example.com');

        $this->assertTrue($email1->isEqual($email2));
    }

    public function testIsEqualReturnsFalseForDifferentEmails(): void
    {
        $email1 = new EmailAddress('test@example.com');
        $email2 = new EmailAddress('other@example.com');

        $this->assertFalse($email1->isEqual($email2));
    }
}
