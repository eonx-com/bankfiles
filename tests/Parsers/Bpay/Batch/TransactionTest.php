<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Bpay\Batch;

use EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Transaction;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class TransactionTest extends TestCase
{
    /**
     * Should return transaction amount as a decimal
     *
     * @group Batch-Transaction
     */
    public function testShouldReturnAmountDecimal(): void
    {
        $transaction = new Transaction(['amount' => '0000000050028']);

        self::assertSame('500.28', $transaction->getAmountDecimal());
    }

    /**
     * Should return transaction amount
     *
     * @group Batch-Transaction
     */
    public function testShouldReturnAmountDecimalNull(): void
    {
        $transaction = new Transaction();

        self::assertNull($transaction->getAmountDecimal());
    }
}
