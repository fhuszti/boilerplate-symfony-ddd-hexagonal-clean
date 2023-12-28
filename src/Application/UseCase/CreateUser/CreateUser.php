<?php

namespace App\Application\UseCase\CreateUser;

use App\Application\Presentation\PresenterInterface;

class CreateUser
{
    public function __construct(
        private readonly CreateUserHandler $createUserHandler
    ) {
    }

    public function execute(CreateUserCommand $command, PresenterInterface $presenter): void
    {
        $user = $this->createUserHandler->handle($command);

        $presenter->present(new CreateUserOutput($user));
    }
}
