<?php

namespace App\Application\Presentation\OutputInterface;

interface ScalarOutputInterface extends OutputInterface
{
    public function getData(): int|float|string|bool;
}
