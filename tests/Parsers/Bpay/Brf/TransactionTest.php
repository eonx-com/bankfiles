<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Bpay\Brf;

use DateTime;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Transaction;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class TransactionTest extends TestCase
{
    /**
     * Should return transaction amount
     *
     * @group Bpay-Transaction
     *
     * @return void
     */
    public function testShouldReturnAmount(): void
    {
        $transaction = new Transaction(['amount' => '000000500025']);

        self::assertEquals(5000.25, $transaction->getAmount());
    }

    /**
     * Should return payment date as DateTime object
     *
     * @group Bpay-Transaction
     *
     * @return void
     */
    public function testShouldReturnPaymentDate(): void
    {
        $transaction = new Transaction(['paymentDate' => '20160426']);

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInstanceOf(DateTime::class, $transaction->getPaymentDate());
    }

    /**
     * Should return settlement date as DateTime object
     *
     * @group Bpay-Transaction
     *
     * @return void
     */
    public function testShouldReturnSettlementDate(): void
    {
        $transaction = new Transaction(['settlementDate' => '20160426']);

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInstanceOf(DateTime::class, $transaction->getSettlementDate());
    }
}
