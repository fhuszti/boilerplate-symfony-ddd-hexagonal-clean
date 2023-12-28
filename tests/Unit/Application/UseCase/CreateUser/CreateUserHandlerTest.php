<?php

namespace App\Tests\Unit\Application\UseCase\CreateUser;

use App\Application\Exception\ApplicationValidationException;
use App\Application\Security\PasswordHasherInterface;
use App\Application\UseCase\CreateUser\CreateUserCommand;
use App\Application\UseCase\CreateUser\CreateUserHandler;
use App\Domain\Security\RoleEnum;
use App\Domain\User\Entity\User;
use App\Domain\User\Persistence\UserGatewayInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserHandlerTest extends TestCase
{
    private MockObject&PasswordHasherInterface $passwordHasherMock;
    private MockObject&UserGatewayInterface $userGatewayMock;
    private CreateUserHandler $createUserHandler;

    protected function setUp(): void
    {
        $this->passwordHasherMock = $this->createMock(PasswordHasherInterface::class);
        $this->userGatewayMock = $this->createMock(UserGatewayInterface::class);
        $this->createUserHandler = new CreateUserHandler($this->passwordHasherMock, $this->userGatewayMock);
    }

    public function testHandleCreatesUserSuccessfully(): void
    {
        $command = new CreateUserCommand('username', 'email@example.com', 'password123', 'admin');

        $this->passwordHasherMock->method('hash')
            ->willReturn('hashedPassword');

        $this->userGatewayMock->expects($this->once())
            ->method('save')
            ->willReturnCallback(fn (User $user) => $user);

        $result = $this->createUserHandler->handle($command);
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('username', $result->getUsername()->getUsername());
        $this->assertEquals('email@example.com', $result->getEmail()->getEmail());
        $this->assertEquals('hashedPassword', $result->getPassword()->getPassword());
        $this->assertEquals(RoleEnum::Admin, $result->getRole());
    }

    public function testHandleThrowsExceptionForInvalidRole(): void
    {
        $this->expectException(ApplicationValidationException::class);
        $this->expectExceptionMessage('No role corresponding to INVALID_ROLE');

        $command = new CreateUserCommand('username', 'email@example.com', 'password', 'INVALID_ROLE');
        $this->createUserHandler->handle($command);
    }
}
