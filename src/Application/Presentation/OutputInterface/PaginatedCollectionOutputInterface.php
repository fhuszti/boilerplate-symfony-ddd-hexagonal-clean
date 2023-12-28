<?php

namespace App\Application\Presentation\OutputInterface;

interface PaginatedCollectionOutputInterface extends OutputInterface
{
    /**
     * @return mixed[]
     */
    public function getData(): array;

    public function getPage(): int;

    public function getSize(): int;

    public function getTotal(): int;
}
