<?php

namespace App\Tests\Unit\Application\UseCase\CreateUser;

use App\Application\Presentation\PresenterInterface;
use App\Application\UseCase\CreateUser\CreateUser;
use App\Application\UseCase\CreateUser\CreateUserCommand;
use App\Application\UseCase\CreateUser\CreateUserHandler;
use App\Application\UseCase\CreateUser\CreateUserOutput;
use App\Domain\User\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserTest extends TestCase
{
    private MockObject&CreateUserHandler $createUserHandlerMock;
    private MockObject&PresenterInterface $presenterMock;
    private CreateUser $createUser;

    protected function setUp(): void
    {
        $this->createUserHandlerMock = $this->createMock(CreateUserHandler::class);
        $this->presenterMock = $this->createMock(PresenterInterface::class);
        $this->createUser = new CreateUser($this->createUserHandlerMock);
    }

    public function testExecuteCallsHandlerAndPresentsOutput(): void
    {
        $command = new CreateUserCommand('username', 'email@example.com', 'password123', 'admin');
        $user = $this->createStub(User::class);

        $this->createUserHandlerMock->expects($this->once())
            ->method('handle')
            ->with($command)
            ->willReturn($user);

        $this->presenterMock->expects($this->once())
            ->method('present')
            ->with(new CreateUserOutput($user));

        $this->createUser->execute($command, $this->presenterMock);
    }
}
