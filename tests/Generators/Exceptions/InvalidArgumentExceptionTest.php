<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Exceptions;

use EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException;
use EoneoPay\Utils\Interfaces\BaseExceptionInterface;
use Tests\EoneoPay\BankFiles\Generators\TestCase;

class InvalidArgumentExceptionTest extends TestCase
{
    /**
     * Exception should return expected error codes.
     */
    public function testErrorCodes(): void
    {
        $exception = new InvalidArgumentException();

        self::assertSame(BaseExceptionInterface::DEFAULT_ERROR_CODE_RUNTIME, $exception->getErrorCode());
        self::assertSame(BaseExceptionInterface::DEFAULT_ERROR_SUB_CODE, $exception->getErrorSubCode());
    }
}
