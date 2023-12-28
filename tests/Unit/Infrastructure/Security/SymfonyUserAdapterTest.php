<?php

namespace App\Tests\Unit\Infrastructure\Security;

use App\Domain\Security\RoleEnum;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\ValueObject\EmailAddress;
use App\Domain\User\Entity\ValueObject\Password;
use App\Infrastructure\Security\SymfonyUserAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SymfonyUserAdapterTest extends TestCase
{
    private MockObject&User $user;
    private SymfonyUserAdapter $symfonyUserAdapter;

    protected function setUp(): void
    {
        $this->user = $this->createMock(User::class);
        $this->symfonyUserAdapter = new SymfonyUserAdapter($this->user);
    }

    public function testGetPassword(): void
    {
        $password = 'hashedPassword123';
        $this->user->method('getPassword')
            ->willReturn(new Password($password));

        $this->assertEquals($password, $this->symfonyUserAdapter->getPassword());
    }

    public function testGetRoles(): void
    {
        $this->user->method('getRole')
            ->willReturn(RoleEnum::Admin);
        $expectedRoles = ['ROLE_ADMIN', 'ROLE_USER'];

        $this->assertEquals($expectedRoles, $this->symfonyUserAdapter->getRoles());
    }

    public function testGetUserIdentifier(): void
    {
        $email = 'user@example.com';
        $this->user->method('getEmail')
            ->willReturn(new EmailAddress($email));

        $this->assertEquals($email, $this->symfonyUserAdapter->getUserIdentifier());
    }
}
