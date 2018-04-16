<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Bpay\Brf;

use EoneoPay\BankFiles\Parsers\Bpay\Brf\Exceptions\InvalidSignFieldException;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Trailer;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class TrailerTest extends TestCase
{
    /**
     * Should return amount of error corrections
     *
     * @group Trailer
     *
     * @return void
     *
     * @throws InvalidSignFieldException
     */
    public function testShouldReturnAmountOfErrorCorrections(): void
    {
        $expected = [
            'amount' => '20.00',
            'type' => 'credit'
        ];

        $trailer = new Trailer([
            'amountOfErrorCorrections' => '00000000000200{'
        ]);

        /** @noinspection UnnecessaryAssertionInspection Assertion neecessary for exact instance type */
        self::assertInternalType('array', $trailer->getAmountOfErrorCorrections());
        self::assertEquals($expected, $trailer->getAmountOfErrorCorrections());
    }

    /**
     * Should return amount of payments
     *
     * @group Trailer
     *
     * @return void
     *
     * @throws InvalidSignFieldException
     */
    public function testShouldReturnAmountOfPayments(): void
    {
        $expected = [
            'amount' => '120.15',
            'type' => 'credit'
        ];

        $trailer = new Trailer([
            'amountOfPayments' => '00000000001201E'
        ]);

        /** @noinspection UnnecessaryAssertionInspection Assertion neecessary for exact instance type */
        self::assertInternalType('array', $trailer->getAmountOfPayments());
        self::assertEquals($expected, $trailer->getAmountOfPayments());
    }

    /**
     * Should return amount of payments
     *
     * @group Trailer
     *
     * @return void
     *
     * @throws InvalidSignFieldException
     */
    public function testShouldReturnAmountOfReversals(): void
    {
        $expected = [
            'amount' => '125.17',
            'type' => 'credit'
        ];

        $trailer = new Trailer([
            'amountOfReversals' => '00000000001251G'
        ]);

        /** @noinspection UnnecessaryAssertionInspection Assertion neecessary for exact instance type */
        self::assertInternalType('array', $trailer->getAmountOfReversals());
        self::assertEquals($expected, $trailer->getAmountOfReversals());
    }

    /**
     * Should return settlement amount
     *
     * @group Trailer
     *
     * @return void
     *
     * @throws InvalidSignFieldException
     */
    public function testShouldReturnSettlementAmount(): void
    {
        $expected = [
            'amount' => '125.17',
            'type' => 'credit'
        ];

        $trailer = new Trailer([
            'settlementAmount' => '00000000001251G'
        ]);

        /** @noinspection UnnecessaryAssertionInspection Assertion neecessary for exact instance type */
        self::assertInternalType('array', $trailer->getSettlementAmount());
        self::assertEquals($expected, $trailer->getSettlementAmount());
    }

    /**
     * Should throw exception if sign field is not found
     *
     * @group Trailer
     *
     * @expectedException \EoneoPay\BankFiles\Parsers\Bpay\Brf\Exceptions\InvalidSignFieldException
     */
    public function testShouldThrowExceptionIfSignedFileNotFound(): void
    {
        $trailer = new Trailer([
            'amountOfErrorCorrections' => '00000000000200W'
        ]);

        $trailer->getAmountOfErrorCorrections();
    }
}
