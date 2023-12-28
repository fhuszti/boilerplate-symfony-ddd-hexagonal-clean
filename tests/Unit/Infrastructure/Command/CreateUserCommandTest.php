<?php

namespace App\Tests\Unit\Infrastructure\Command;

use App\Application\Exception\ApplicationValidationException;
use App\Application\UseCase\CreateUser\CreateUser;
use App\Domain\Security\RoleEnum;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\ValueObject\EmailAddress;
use App\Domain\User\Entity\ValueObject\Username;
use App\Infrastructure\Command\CreateUserCommand;
use App\Infrastructure\Presentation\RawPresenter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends TestCase
{
    private MockObject&CreateUser $createUserMock;
    private MockObject&RawPresenter $rawPresenterMock;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->rawPresenterMock = $this->createMock(RawPresenter::class);
        $this->createUserMock = $this->createMock(CreateUser::class);

        $command = new CreateUserCommand($this->createUserMock, $this->rawPresenterMock);
        $this->commandTester = new CommandTester($command);
    }

    private function generateUserData(): User
    {
        $username = new Username('username');
        $emailAddress = new EmailAddress('email@example.com');

        $createdUser = $this->createMock(User::class);
        $createdUser->expects($this->once())
            ->method('getId')
            ->willReturn(1);
        $createdUser->expects($this->once())
            ->method('getUsername')
            ->willReturn($username);
        $createdUser->expects($this->once())
            ->method('getEmail')
            ->willReturn($emailAddress);
        $createdUser->expects($this->once())
            ->method('getRole')
            ->willReturn(RoleEnum::Admin);

        return $createdUser;
    }

    public function testExecuteWithValidData(): void
    {
        $createdUser = $this->generateUserData();

        $this->createUserMock->expects($this->once())
            ->method('execute');
        $this->rawPresenterMock->expects($this->once())
            ->method('getData')
            ->willReturn($createdUser);

        $this->commandTester->setInputs([
            'username',
            'email@example.com',
            'password123',
            'admin',
        ]);
        $this->commandTester->execute([]);

        $this->commandTester->assertCommandIsSuccessful();
        $this->assertStringContainsString('User has been created successfully!', $this->commandTester->getDisplay());
    }

    public function testExecuteWithInvalidData(): void
    {
        $this->createUserMock->expects($this->once())
            ->method('execute')
            ->willThrowException(new ApplicationValidationException('EmailAddress', 'Invalid email address format'));

        $this->commandTester->setInputs([
            'username',
            'invalid_email',
            'password123',
            'admin',
        ]);
        $this->commandTester->execute([]);

        $this->assertEquals(Command::FAILURE, $this->commandTester->getStatusCode());
        $this->assertStringContainsString('Invalid email address', $this->commandTester->getDisplay());
    }
}
