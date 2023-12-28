<?php

namespace App\Infrastructure\Security;

use App\Application\Security\PasswordHasherInterface;
use App\Domain\User\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class PasswordHasherAdapter implements PasswordHasherInterface
{
    public function __construct(
        private UserPasswordHasherInterface $symfonyHasher
    ) {
    }

    public function hash(User $user, string $plainPassword): string
    {
        $symfonyUser = new SymfonyUserAdapter($user);

        return $this->symfonyHasher->hashPassword(
            $symfonyUser,
            $plainPassword
        );
    }

    public function isValid(User $user, string $plainPassword): bool
    {
        $symfonyUser = new SymfonyUserAdapter($user);

        return $this->symfonyHasher->isPasswordValid(
            $symfonyUser,
            $plainPassword
        );
    }
}
