<?php

namespace App\Tests\Unit\Infrastructure\Exception;

use App\Infrastructure\Exception\InvalidTypeException;
use PHPUnit\Framework\TestCase;

class InvalidTypeExceptionTest extends TestCase
{
    public function testExceptionMessageIsFormattedCorrectly(): void
    {
        $expectedType = 'string';
        $gotType = 'integer';

        $exception = new InvalidTypeException($expectedType, $gotType);

        $expectedMessage = sprintf(InvalidTypeException::INVALID_TYPE_MESSAGE, $expectedType, $gotType);
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }
}
