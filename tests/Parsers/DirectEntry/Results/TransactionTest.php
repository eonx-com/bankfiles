<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\DirectEntry\Results;

use EoneoPay\BankFiles\Parsers\DirectEntry\Results\Transaction;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\DirectEntry\Results\Transaction
 */
class TransactionTest extends TestCase
{
    /**
     * Test if amount conversion works as expected
     *
     * @return void
     */
    public function testAmountConversion(): void
    {
        $transaction = new Transaction([
            'amount' => '0000000000'
        ]);

        self::assertSame('0.00', $transaction->getAmount());
    }

    /**
     * Check if amount conversion works if 8 digit withholding tax provided
     *
     * @return void
     */
    public function testAmountConversionWorksOn8Digit(): void
    {
        $transaction = new Transaction([
            'withholdingTax' => '00000890'
        ]);

        self::assertSame('8.90', $transaction->getWithholdingTax());
    }
}
