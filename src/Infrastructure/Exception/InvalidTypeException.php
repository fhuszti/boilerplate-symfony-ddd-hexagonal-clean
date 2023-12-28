<?php

namespace App\Infrastructure\Exception;

class InvalidTypeException extends InfrastructureException
{
    public const string INVALID_TYPE_MESSAGE = 'Invalid type. Expected %s, got %s';

    public function __construct(string $expected, string $got)
    {
        parent::__construct($this->format($expected, $got));
    }

    private function format(string $expected, string $got): string
    {
        return sprintf(self::INVALID_TYPE_MESSAGE, $expected, $got);
    }
}
