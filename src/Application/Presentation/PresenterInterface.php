<?php

namespace App\Application\Presentation;

use App\Application\Presentation\OutputInterface\OutputInterface;

interface PresenterInterface
{
    public function present(OutputInterface $output): void;
}
