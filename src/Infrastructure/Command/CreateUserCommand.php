<?php

namespace App\Infrastructure\Command;

use App\Application\UseCase\CreateUser\CreateUser;
use App\Application\UseCase\CreateUser\CreateUserCommand as CreateUserCommandDTO;
use App\Domain\Security\RoleEnum;
use App\Infrastructure\Exception\InvalidTypeException;
use App\Infrastructure\Presentation\RawPresenter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'user:create', description: 'Creates a new user')]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly CreateUser $createUser,
        private readonly RawPresenter $rawPresenter
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to create a user manually')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('User Creator');

        try {
            $io->section('Data');

            $username = $io->ask('Username?');
            if (!is_string($username)) {
                throw new InvalidTypeException('string', gettype($username));
            }

            $email = $io->ask('Email address?');
            if (!is_string($email)) {
                throw new InvalidTypeException('string', gettype($email));
            }

            $password = $io->ask('Password?');
            if (!is_string($password)) {
                throw new InvalidTypeException('string', gettype($password));
            }

            $role = $io->choice('Role?', RoleEnum::values(), RoleEnum::User->value);
            if (!is_string($role)) {
                throw new InvalidTypeException('string', gettype($role));
            }

            $io->section('Creating the user');

            $progressIndicator = new ProgressIndicator($output, 'verbose', 100, ['⠏', '⠛', '⠹', '⢸', '⣰', '⣤', '⣆', '⡇']);
            $progressIndicator->start('Processing...');

            $command = new CreateUserCommandDTO($username, $email, $password, $role);
            $this->createUser->execute($command, $this->rawPresenter);

            $progressIndicator->finish('Finished');
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $io->success('User has been created successfully!');

        return Command::SUCCESS;
    }
}
