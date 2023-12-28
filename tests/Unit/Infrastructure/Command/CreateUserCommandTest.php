<?php

namespace App\Tests\Unit\Infrastructure\Command;

use App\Application\Exception\ApplicationValidationException;
use App\Application\UseCase\CreateUser\CreateUser;
use App\Infrastructure\Command\CreateUserCommand;
use App\Infrastructure\Presentation\RawPresenter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends TestCase
{
    private MockObject&CreateUser $createUserMock;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $rawPresenterMock = $this->createMock(RawPresenter::class);
        $this->createUserMock = $this->createMock(CreateUser::class);

        $command = new CreateUserCommand($this->createUserMock, $rawPresenterMock);
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteWithValidData(): void
    {
        $this->createUserMock->expects($this->once())
            ->method('execute');

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
