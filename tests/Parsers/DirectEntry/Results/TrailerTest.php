<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\DirectEntry\Results;

use EoneoPay\BankFiles\Parsers\DirectEntry\Results\Trailer;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\DirectEntry\Results\Trailer
 */
class TrailerTest extends TestCase
{
    /**
     * Test amount conversion works as expected
     */
    public function testAmountConversionWorksAsExpected(): void
    {
        $trailer = new Trailer([
            'totalCreditAmount' => '0000023857',
        ]);

        self::assertSame('238.57', $trailer->getTotalCreditAmount());
    }

    /**
     * Test amount conversions if invalid data provided
     */
    public function testAmountConversionsIfInvalidData(): void
    {
        $trailer = new Trailer([
            'totalNetAmount' => '000',
        ]);

        self::assertSame('0.00', $trailer->getTotalNetAmount());
    }

    /**
     * Test amount conversions if no data provided
     */
    public function testAmountConversionsIfNoData(): void
    {
        $trailer = new Trailer();

        self::assertSame('0.00', $trailer->getTotalDebitAmount());
    }
}
