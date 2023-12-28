<?php

namespace App\Infrastructure\Presentation;

use App\Infrastructure\Exception\InfrastructureException;

class PresenterException extends InfrastructureException
{
    public const INVALID_OUTPUT_INTERFACE = 'Invalid OutputInterface: %s';

    public function __construct(string $parameter)
    {
        parent::__construct($this->format($parameter));
    }

    private function format(string $parameter): string
    {
        return sprintf(self::INVALID_OUTPUT_INTERFACE, $parameter);
    }
}
