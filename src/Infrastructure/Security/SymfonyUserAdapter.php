<?php

namespace App\Infrastructure\Security;

use App\Domain\Security\RoleEnum;
use App\Domain\User\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SymfonyUserAdapter implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private readonly User $user
    ) {
    }

    public function getDomainUser(): User
    {
        return $this->user;
    }

    public function getPassword(): ?string
    {
        return $this->user->getPassword()->getPassword();
    }

    public function getRoles(): array
    {
        $domainRole = $this->user->getRole();
        $roles = [$this->getSymfonyRoleFromDomainRole($domainRole)];
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    private function getSymfonyRoleFromDomainRole(RoleEnum $domainRole): string
    {
        return match ($domainRole) {
            RoleEnum::Free => 'ROLE_USER',
            RoleEnum::Subscribed => 'ROLE_CUSTOMER',
            RoleEnum::Admin => 'ROLE_ADMIN'
        };
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getEmail()->getEmail();
    }
}
