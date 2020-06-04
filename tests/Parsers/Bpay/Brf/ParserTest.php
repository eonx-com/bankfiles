<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Bpay\Brf;

use EoneoPay\BankFiles\Parsers\Bpay\Brf\Parser;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Header;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Trailer;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Transaction;
use EoneoPay\Utils\Collection;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class ParserTest extends TestCase
{
    /**
     * Should return error from the content
     *
     * @group Brf-Parser-Error
     */
    public function testShouldReturnErrors(): void
    {
        $brfParser = new Parser($this->getSampleFileContents('sample.BRF'));

        self::assertInstanceOf(Collection::class, $brfParser->getErrors());
    }

    /**
     * Should return Header object
     *
     * @group Brf-Parser-Header
     */
    public function testShouldReturnHeader(): void
    {
        $brfParser = new Parser($this->getSampleFileContents('sample.BRF'));

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInstanceOf(Header::class, $brfParser->getHeader());
    }

    /**
     * Should return trailer from the content
     *
     * @group Brf-Parser-Trailer
     */
    public function testShouldReturnTrailer(): void
    {
        $brfParser = new Parser($this->getSampleFileContents('sample.BRF'));

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInstanceOf(Trailer::class, $brfParser->getTrailer());
    }

    /**
     * Should return Transaction and TransactionItem class
     *
     * @group Brf-Parser-Transaction
     */
    public function testShouldReturnTransaction(): void
    {
        $brfParser = new Parser($this->getSampleFileContents('sample.BRF'));

        $transactions = $brfParser->getTransactions();

        self::assertInstanceOf(Collection::class, $transactions);

        /** @var \EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Transaction $firstTransactionItem */
        $firstTransactionItem = $transactions->first();

        self::assertInstanceOf(Transaction::class, $firstTransactionItem);
    }

    /**
     * Get sample file contents.
     */
    private function getSampleFileContents(string $file): string
    {
        return \file_get_contents(\realpath(__DIR__) . '/data/' . $file) ?: '';
    }
}
