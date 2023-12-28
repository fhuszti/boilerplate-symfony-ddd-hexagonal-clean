<?php

namespace App\Application\Presentation\OutputInterface;

interface ObjectOutputInterface extends OutputInterface
{
    public function getObject(): object;
}
