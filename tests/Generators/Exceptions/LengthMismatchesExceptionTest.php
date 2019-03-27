<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Exceptions;

use EoneoPay\BankFiles\Generators\Exceptions\LengthMismatchesException;
use EoneoPay\Utils\Interfaces\BaseExceptionInterface;
use Tests\EoneoPay\BankFiles\Generators\TestCase;

class LengthMismatchesExceptionTest extends TestCase
{
    /**
     * Exception should return expected error codes.
     *
     * @return void
     */
    public function testErrorCodes(): void
    {
        $exception = new LengthMismatchesException();

        self::assertEquals(BaseExceptionInterface::DEFAULT_ERROR_CODE_RUNTIME, $exception->getErrorCode());
        self::assertEquals(BaseExceptionInterface::DEFAULT_ERROR_SUB_CODE, $exception->getErrorSubCode());
    }
}
