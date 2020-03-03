<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Exceptions;

use EoneoPay\BankFiles\Exceptions\ImpossibleException;
use Tests\EoneoPay\BankFiles\TestCases\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Exceptions\ImpossibleException
 */
class ImpossibleExceptionTest extends TestCase
{
    /**
     * Tests that the exception codes match the expected.
     *
     * @return void
     */
    public function testExceptionCodes(): void
    {
        $exception = new ImpossibleException();

        self::assertSame(1999, $exception->getErrorCode());
        self::assertSame(1, $exception->getErrorSubCode());
    }
}
