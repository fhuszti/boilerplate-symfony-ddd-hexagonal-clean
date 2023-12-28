<?php

namespace App\Application\Presentation\OutputInterface;

interface CollectionOutputInterface extends OutputInterface
{
    /**
     * @return mixed[]
     */
    public function getArray(): array;
}
