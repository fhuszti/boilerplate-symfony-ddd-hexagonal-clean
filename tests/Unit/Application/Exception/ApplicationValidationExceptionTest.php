<?php

namespace App\Tests\Unit\Application\Exception;

use App\Application\Exception\ApplicationValidationException;
use PHPUnit\Framework\TestCase;

class ApplicationValidationExceptionTest extends TestCase
{
    public function testExceptionMessageIsFormattedCorrectly(): void
    {
        $parameter = 'testParameter';
        $message = 'test message';

        $exception = new ApplicationValidationException($parameter, $message);

        $expectedMessage = sprintf(ApplicationValidationException::VALIDATION_EXCEPTION_MESSAGE, $parameter, $message);
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }
}
