<?php

namespace App\Application\Exception;

class ApplicationValidationException extends ApplicationException
{
    public const VALIDATION_EXCEPTION_MESSAGE = 'Validation failed for parameter %s: %s';

    public function __construct(string $parameter, string $message)
    {
        parent::__construct($this->format($parameter, $message));
    }

    private function format(string $parameter, string $message): string
    {
        return sprintf(self::VALIDATION_EXCEPTION_MESSAGE, $parameter, $message);
    }
}
