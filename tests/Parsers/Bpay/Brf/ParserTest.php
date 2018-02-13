<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Bpay\Brf;

use EoneoPay\BankFiles\Parsers\Bpay\Brf\Parser;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Header;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Trailer;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Transaction;
use Illuminate\Support\Collection;
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
        $filename = \realpath(__DIR__ . '/data') . '/sample.BRF';
        $content = \file_get_contents($filename);

        $brfParser = new Parser($content);

        self::assertInstanceOf(Collection::class, $brfParser->getErrors());
    }

    /**
     * Should return Header object
     *
     * @group Brf-Parser-Header
     *
     * @return void
     */
    public function testShouldReturnHeader(): void
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.BRF';
        $content = \file_get_contents($filename);

        $brfParser = new Parser($content);

        self::assertInstanceOf(Header::class, $brfParser->getHeader());
    }

    /**
     * Should return trailer from the content
     *
     * @group Brf-Parser-Trailer
     */
    public function testShouldReturnTrailer(): void
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.BRF';
        $content = \file_get_contents($filename);

        $brfParser = new Parser($content);

        self::assertInstanceOf(Trailer::class, $brfParser->getTrailer());
    }

    /**
     * Should return Transaction and TransactionItem class
     *
     * @group Brf-Parser-Transaction
     *
     * @return void
     */
    public function testShouldReturnTransaction(): void
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.BRF';
        $content = \file_get_contents($filename);

        $brfParser = new Parser($content);

        $transactions = $brfParser->getTransactions();

        self::assertInstanceOf(Collection::class, $transactions);
        /** @var Transaction $firstTransactionItem */
        $firstTransactionItem = $transactions->first();

        if ($firstTransactionItem) {
            self::assertInstanceOf(Transaction::class, $firstTransactionItem);
        }
    }
}
