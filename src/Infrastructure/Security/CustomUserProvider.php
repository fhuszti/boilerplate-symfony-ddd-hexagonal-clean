<?php

namespace App\Infrastructure\Security;

use App\Domain\User\Entity\User;
use App\Domain\User\Entity\ValueObject\Password;
use App\Domain\User\Persistence\UserGatewayInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class CustomUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private UserGatewayInterface $userGateway
    ) {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userGateway->getByEmail($identifier);
        if (!$user) {
            throw new UserNotFoundException('User not found with email: '.$identifier);
        }

        return new SymfonyUserAdapter($user);
    }

    /** Won't ever be called because the firewalls are stateless */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }
        throw new \Exception('TODO: fill in refreshUser() inside '.__FILE__);
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof SymfonyUserAdapter) {
            return;
        }

        try {
            $domainUser = $user->getDomainUser();
            $domainUser->setPassword(new Password($newHashedPassword));
            $this->userGateway->save($domainUser);
        } catch (\Exception $e) {
            // This should be opportunistic, so it's fine to do nothing here and have it fail silently
        }
    }
}
