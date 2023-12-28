<?php

namespace App\Tests\Unit\Infrastructure\Security;

use App\Domain\User\Entity\User;
use App\Domain\User\Entity\ValueObject\Password;
use App\Domain\User\Persistence\UserGatewayInterface;
use App\Infrastructure\Security\CustomUserProvider;
use App\Infrastructure\Security\SymfonyUserAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class CustomUserProviderTest extends TestCase
{
    private MockObject&UserGatewayInterface $userGateway;
    private CustomUserProvider $customUserProvider;

    protected function setUp(): void
    {
        $this->userGateway = $this->createMock(UserGatewayInterface::class);
        $this->customUserProvider = new CustomUserProvider($this->userGateway);
    }

    public function testLoadUserByIdentifierWithFoundUser(): void
    {
        $userEmail = 'user@example.com';
        $user = $this->createMock(User::class);

        $this->userGateway->method('getByEmail')
            ->with($userEmail)
            ->willReturn($user);

        $result = $this->customUserProvider->loadUserByIdentifier($userEmail);
        $this->assertInstanceOf(SymfonyUserAdapter::class, $result);
    }

    public function testLoadUserByIdentifierWithUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->userGateway->method('getByEmail')
            ->willReturn(null);

        $this->customUserProvider->loadUserByIdentifier('nonexistent@example.com');
    }

    public function testSupportsClass(): void
    {
        $this->assertTrue($this->customUserProvider->supportsClass(User::class));
        $this->assertFalse($this->customUserProvider->supportsClass(\stdClass::class));
    }

    public function testUpgradePasswordWithSymfonyUserAdapter(): void
    {
        $newHashedPassword = 'newHashedPassword';
        $domainUser = $this->createMock(User::class);
        $domainUser->expects($this->once())
            ->method('setPassword') // Assuming setPassword is a method of your User entity
            ->with(new Password($newHashedPassword));

        $this->userGateway->expects($this->once())
            ->method('save')
            ->with($domainUser);

        $symfonyUserAdapter = $this->createMock(SymfonyUserAdapter::class);
        $symfonyUserAdapter->method('getDomainUser')
            ->willReturn($domainUser);

        $this->customUserProvider->upgradePassword($symfonyUserAdapter, $newHashedPassword);
    }

    public function testUpgradePasswordWithUnsupportedUser(): void
    {
        $this->userGateway->expects($this->never())
            ->method('save');

        $unsupportedUser = $this->createMock(PasswordAuthenticatedUserInterface::class);
        $this->customUserProvider->upgradePassword($unsupportedUser, 'newHashedPassword');
    }
}
