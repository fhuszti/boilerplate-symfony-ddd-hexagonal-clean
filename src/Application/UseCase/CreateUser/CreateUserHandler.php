<?php

namespace App\Application\UseCase\CreateUser;

use App\Application\Exception\ApplicationValidationException;
use App\Application\Security\PasswordHasherInterface;
use App\Domain\Security\PasswordValidator;
use App\Domain\Security\RoleEnum;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\ValueObject\EmailAddress;
use App\Domain\User\Entity\ValueObject\Password;
use App\Domain\User\Entity\ValueObject\Username;
use App\Domain\User\Persistence\UserGatewayInterface;

class CreateUserHandler
{
    public function __construct(
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly UserGatewayInterface $userGateway
    ) {
    }

    public function handle(CreateUserCommand $command): User
    {
        $user = new User();
        $user->setUsername(new Username($command->getUsername()));
        $user->setEmail(new EmailAddress($command->getEmailAddress()));
        $user->setPassword($this->generatePassword($user, $command->getPassword()));
        $user->setRole($this->getRoleFromString($command->getRole()));

        return $this->userGateway->save($user);
    }

    private function getRoleFromString(string $strRole): RoleEnum
    {
        $role = RoleEnum::tryFrom($strRole);
        if (null === $role) {
            throw new ApplicationValidationException('Role', 'No role corresponding to '.$strRole);
        }

        return $role;
    }

    private function generatePassword(User $user, string $plainPassword): Password
    {
        PasswordValidator::validate($plainPassword);
        $hashedPassword = $this->passwordHasher->hash($user, $plainPassword);

        return new Password($hashedPassword);
    }
}
