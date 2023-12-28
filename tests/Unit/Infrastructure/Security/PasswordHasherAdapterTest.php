<?php

namespace App\Tests\Unit\Infrastructure\Security;

use App\Domain\User\Entity\User;
use App\Infrastructure\Security\PasswordHasherAdapter;
use App\Infrastructure\Security\SymfonyUserAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherAdapterTest extends TestCase
{
    private MockObject&UserPasswordHasherInterface $symfonyHasher;
    private PasswordHasherAdapter $passwordHasherAdapter;

    protected function setUp(): void
    {
        $this->symfonyHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->passwordHasherAdapter = new PasswordHasherAdapter($this->symfonyHasher);
    }

    public function testHash(): void
    {
        $user = $this->createMock(User::class);
        $plainPassword = 'password123';
        $hashedPassword = 'hashedPassword123';

        $this->symfonyHasher->expects($this->once())
            ->method('hashPassword')
            ->with(
                $this->callback(function ($subject) use ($user) {
                    return $subject instanceof SymfonyUserAdapter && $subject->getDomainUser() === $user;
                }),
                $this->equalTo($plainPassword)
            )
            ->willReturn($hashedPassword);

        $result = $this->passwordHasherAdapter->hash($user, $plainPassword);

        $this->assertEquals($hashedPassword, $result);
    }

    public function testIsValid(): void
    {
        $user = $this->createMock(User::class);
        $plainPassword = 'password123';

        $this->symfonyHasher->expects($this->once())
            ->method('isPasswordValid')
            ->with(
                $this->callback(function ($symfonyUser) use ($user) {
                    return $symfonyUser instanceof SymfonyUserAdapter && $symfonyUser->getDomainUser() === $user;
                }),
                $this->equalTo($plainPassword)
            )
            ->willReturn(true);

        $isValid = $this->passwordHasherAdapter->isValid($user, $plainPassword);

        $this->assertTrue($isValid);
    }

    public function testIsInvalid(): void
    {
        $user = $this->createMock(User::class);
        $plainPassword = 'wrongPassword';

        $this->symfonyHasher->expects($this->once())
            ->method('isPasswordValid')
            ->with(
                $this->callback(function ($symfonyUser) use ($user) {
                    return $symfonyUser instanceof SymfonyUserAdapter && $symfonyUser->getDomainUser() === $user;
                }),
                $this->equalTo($plainPassword)
            )
            ->willReturn(false);

        $isValid = $this->passwordHasherAdapter->isValid($user, $plainPassword);

        $this->assertFalse($isValid);
    }
}
