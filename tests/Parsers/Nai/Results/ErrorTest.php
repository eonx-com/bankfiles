<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Results\Error;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\Error
 */
class ErrorTest extends TestCase
{
    /**
     * Result should return data as expected.
     *
     * @return void
     */
    public function testGetDataAsExpected(): void
    {
        $data = [
            'line' => 'line',
            'lineNumber' => 23
        ];

        $error = new Error($data);

        self::assertSame($data['line'], $error->getLine());
        self::assertSame($data['lineNumber'], $error->getLineNumber());
    }
}
