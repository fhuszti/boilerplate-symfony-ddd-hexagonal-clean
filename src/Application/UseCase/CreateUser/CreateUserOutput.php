<?php

namespace App\Application\UseCase\CreateUser;

use App\Application\Presentation\OutputInterface\ObjectOutputInterface;
use App\Domain\User\Entity\User;

readonly class CreateUserOutput implements ObjectOutputInterface
{
    public function __construct(
        private User $user
    ) {
    }

    public function getObject(): object
    {
        return $this->user;
    }
}
