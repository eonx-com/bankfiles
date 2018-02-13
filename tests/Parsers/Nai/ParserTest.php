<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai;

use EoneoPay\BankFiles\Parsers\Nai\Parser;
use EoneoPay\BankFiles\Parsers\Nai\Results\FileHeader;
use EoneoPay\BankFiles\Parsers\Nai\Results\FileTrailer;
use EoneoPay\BankFiles\Parsers\Nai\Results\GroupHeader;
use EoneoPay\BankFiles\Parsers\Nai\Results\GroupTrailer;
use Illuminate\Support\Collection;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class ParserTest extends TestCase
{
    /**
     * Account should be equal to transaction's account
     *
     * @group Nai-Parser-Accounts-Transactions
     *
     * @return void
     */
    public function testAccountShouldBeEqualToTransactionsAccount(): void
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);

        self::assertEquals($parser->getAccounts()->last(), $parser->getTransactions()->last()->getAccount());
    }

    /**
     * Should return collection of accounts
     *
     * @group Nai-Parser-Accounts
     *
     * @return void
     */
    public function testShouldReturnAccount(): void
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);

        self::assertInstanceOf(Collection::class, $parser->getAccounts());
    }

    /**
     * Should return Error class
     *
     * @group Nai-Parser-Errors
     *
     * @return void
     */
    public function testShouldReturnErrors()
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);

        self::assertInstanceOf(Collection::class, $parser->getErrors());
    }

    /**
     * Should return FileHeader class
     *
     * @group Nai-Parser
     *
     * @return void
     */
    public function testShouldReturnFileHeader()
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);

        self::assertInstanceOf(FileHeader::class, $parser->getFileHeader());
    }

    /**
     * Should return FileTrailer class
     *
     * @group Nai-Parser-File-Trailer
     *
     * @return void
     */
    public function testShouldReturnFileTrailer()
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);

        self::assertInstanceOf(FileTrailer::class, $parser->getFileTrailer());
    }

    /**
     * Should return GroupHeader class
     *
     * @group Nai-Parser-Group-Header
     *
     * @return void
     */
    public function testShouldReturnGroupHeader()
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);

        self::assertInstanceOf(GroupHeader::class, $parser->getGroupHeader());
    }

    /**
     * Should return GroupTrailer class
     *
     * @group Nai-Parser-Group-Trailer
     *
     * @return void
     */
    public function testShouldReturnGroupTrailer()
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);

        self::assertInstanceOf(GroupTrailer::class, $parser->getGroupTrailer());
    }

    /**
     * Should return collection of transactions of all accounts
     *
     * @group Nai-Parser-Accounts-Transactions
     *
     * @return void
     */
    public function testShouldReturnTransactions(): void
    {
        $filename = \realpath(__DIR__ . '/data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);

        self::assertInstanceOf(Collection::class, $parser->getTransactions());
    }
}
