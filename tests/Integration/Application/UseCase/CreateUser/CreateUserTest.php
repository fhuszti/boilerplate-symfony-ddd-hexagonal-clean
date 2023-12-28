<?php

namespace App\Tests\Integration\Application\UseCase\CreateUser;

use App\Application\Presentation\PresenterInterface;
use App\Application\UseCase\CreateUser\CreateUser;
use App\Application\UseCase\CreateUser\CreateUserCommand;
use App\Domain\Security\RoleEnum;
use App\Domain\User\Persistence\UserGatewayInterface;
use App\Infrastructure\Security\PasswordHasherAdapter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateUserTest extends KernelTestCase
{
    public function testCreateUserIntegration(): void
    {
        self::bootKernel(['debug' => false]);
        $container = static::getContainer();

        $presenterInterface = $container->get(PresenterInterface::class);
        $command = new CreateUserCommand('username', 'email@example.com', 'password123', 'admin');

        $useCase = $container->get(CreateUser::class);
        $useCase->execute($command, $presenterInterface);

        /** @var PasswordHasherAdapter $passwordHasher */
        $passwordHasher = $container->get(PasswordHasherAdapter::class);
        /** @var UserGatewayInterface $userGateway */
        $userGateway = $container->get(UserGatewayInterface::class);

        $createdUser = $userGateway->getByEmail('email@example.com');
        $this->assertNotNull($createdUser);

        $this->assertEquals('username', $createdUser->getUsername()->getUsername());
        $this->assertTrue($passwordHasher->isValid($createdUser, 'password123'));
        $this->assertEquals(RoleEnum::Admin, $createdUser->getRole());

        $userGateway->delete($createdUser);
    }
}
