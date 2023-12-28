<?php

namespace App\Infrastructure\Presentation;

use App\Application\Presentation\OutputInterface\CollectionOutputInterface;
use App\Application\Presentation\OutputInterface\ObjectOutputInterface;
use App\Application\Presentation\OutputInterface\OutputInterface;
use App\Application\Presentation\OutputInterface\PaginatedCollectionOutputInterface;
use App\Application\Presentation\OutputInterface\ScalarOutputInterface;
use App\Application\Presentation\PaginatedResponseKeyEnum;
use App\Application\Presentation\PresenterInterface;

class RawPresenter implements PresenterInterface
{
    /** @var int|float|string|bool|mixed[]|object */
    private int|float|string|bool|array|object $data;

    public function present(OutputInterface $output): void
    {
        if ($output instanceof CollectionOutputInterface) {
            $this->data = $output->getArray();
        } elseif ($output instanceof ObjectOutputInterface) {
            $this->data = $output->getObject();
        } elseif ($output instanceof ScalarOutputInterface) {
            $this->data = $output->getData();
        } elseif ($output instanceof PaginatedCollectionOutputInterface) {
            $this->data = [
                PaginatedResponseKeyEnum::Data->value => $output->getData(),
                PaginatedResponseKeyEnum::Page->value => $output->getPage(),
                PaginatedResponseKeyEnum::Size->value => $output->getSize(),
                PaginatedResponseKeyEnum::Total->value => $output->getTotal(),
            ];
        } else {
            throw new PresenterException(get_class($output));
        }
    }

    /**
     * @return int|float|string|bool|mixed[]|object
     */
    public function getData(): int|float|string|bool|array|object
    {
        return $this->data;
    }
}
