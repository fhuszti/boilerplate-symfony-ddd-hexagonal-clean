<?php

namespace App\Tests\Unit\Infrastructure\Presentation;

use App\Infrastructure\Presentation\PresenterException;
use PHPUnit\Framework\TestCase;

class PresenterExceptionTest extends TestCase
{
    public function testExceptionMessageIsFormattedCorrectly(): void
    {
        $parameter = 'SomeInvalidOutputInterface';

        $exception = new PresenterException($parameter);

        $expectedMessage = sprintf(PresenterException::INVALID_OUTPUT_INTERFACE, $parameter);
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }
}
