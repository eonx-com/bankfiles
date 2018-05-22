<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Exceptions;

use EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException;
use EoneoPay\BankFiles\Generators\Exceptions\LengthMismatchesException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException;
use EoneoPay\Utils\Interfaces\BaseExceptionInterface;
use Tests\EoneoPay\BankFiles\Generators\TestCase;

class ValidationFailedExceptionTest extends TestCase
{
    /**
     * Exception should return expected error codes.
     *
     * @return void
     */
    public function testErrorCodes(): void
    {
        $exception = new ValidationFailedException([]);

        self::assertEquals(BaseExceptionInterface::DEFAULT_ERROR_CODE_VALIDATION, $exception->getErrorCode());
        self::assertEquals(BaseExceptionInterface::DEFAULT_ERROR_SUB_CODE, $exception->getErrorSubCode());
    }
}
