<?php

namespace App\Tests\Unit\Domain\Exception;

use App\Domain\Exception\DomainValidationException;
use PHPUnit\Framework\TestCase;

class DomainValidationExceptionTest extends TestCase
{
    public function testExceptionMessageIsFormattedCorrectly(): void
    {
        $parameter = 'username';
        $message = 'must be at least 3 characters long';

        $exception = new DomainValidationException($parameter, $message);

        $expectedMessage = sprintf(DomainValidationException::VALIDATION_EXCEPTION_MESSAGE, $parameter, $message);
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }
}
