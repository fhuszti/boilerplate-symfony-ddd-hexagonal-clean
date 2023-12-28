<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Persistence\UserGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): User
    {
        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    public function getByEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = $this->findOneBy(['email.email' => $email]);

        return $user;
    }

    public function delete(User $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }
}
