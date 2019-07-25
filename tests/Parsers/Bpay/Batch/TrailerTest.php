<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Bpay\Batch;

use EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Trailer;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class TrailerTest extends TestCase
{
    /**
     * Should return total amounts as a decimal
     *
     * @group Batch-Trailer
     *
     * @return void
     */
    public function testShouldReturnAmountsDecimal(): void
    {
        $trailer = new Trailer([
            'amountOfApprovals' => '0000000030018',
            'amountOfDeclines' => '0000000020010',
            'amountOfPayments' => '0000000050028'
        ]);

        self::assertSame('300.18', $trailer->getAmountOfApprovalsDecimal());
        self::assertSame('200.10', $trailer->getAmountOfDeclinesDecimal());
        self::assertSame('500.28', $trailer->getAmountOfPaymentsDecimal());
    }

    /**
     * Should return total amounts as a null
     *
     * @group Batch-Trailer
     *
     * @return void
     */
    public function testShouldReturnAmountsDecimalNull(): void
    {
        $trailer = new Trailer();

        self::assertNull($trailer->getAmountOfApprovalsDecimal());
        self::assertNull($trailer->getAmountOfDeclinesDecimal());
        self::assertNull($trailer->getAmountOfPaymentsDecimal());
    }
}
