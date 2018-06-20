<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai;

use EoneoPay\BankFiles\Parsers\Nai\Parser;
use EoneoPay\BankFiles\Parsers\Nai\Results\FileHeader;
use EoneoPay\BankFiles\Parsers\Nai\Results\FileTrailer;
use EoneoPay\BankFiles\Parsers\Nai\Results\GroupHeader;
use EoneoPay\BankFiles\Parsers\Nai\Results\GroupTrailer;
use EoneoPay\Utils\Collection;
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
        $parser = new Parser($this->getSampleFileContents('sample.NAI'));

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
        $parser = new Parser($this->getSampleFileContents('sample.NAI'));

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInstanceOf(Collection::class, $parser->getAccounts());
    }

    /**
     * Should return Error class
     *
     * @group Nai-Parser-Errors
     *
     * @return void
     */
    public function testShouldReturnErrors(): void
    {
        $parser = new Parser($this->getSampleFileContents('sample.NAI'));

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInstanceOf(Collection::class, $parser->getErrors());
    }

    /**
     * Should return FileHeader class
     *
     * @group Nai-Parser
     *
     * @return void
     */
    public function testShouldReturnFileHeader(): void
    {
        $parser = new Parser($this->getSampleFileContents('sample.NAI'));

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInstanceOf(FileHeader::class, $parser->getFileHeader());
    }

    /**
     * Should return FileTrailer class
     *
     * @group Nai-Parser-File-Trailer
     *
     * @return void
     */
    public function testShouldReturnFileTrailer(): void
    {
        $parser = new Parser($this->getSampleFileContents('sample.NAI'));

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInstanceOf(FileTrailer::class, $parser->getFileTrailer());
    }

    /**
     * Should return GroupHeader class
     *
     * @group Nai-Parser-Group-Header
     *
     * @return void
     */
    public function testShouldReturnGroupHeader(): void
    {
        $parser = new Parser($this->getSampleFileContents('sample.NAI'));

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInstanceOf(GroupHeader::class, $parser->getGroupHeader());
    }

    /**
     * Should return GroupTrailer class
     *
     * @group Nai-Parser-Group-Trailer
     *
     * @return void
     */
    public function testShouldReturnGroupTrailer(): void
    {
        $parser = new Parser($this->getSampleFileContents('sample.NAI'));

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
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
        $parser = new Parser($this->getSampleFileContents('sample.NAI'));

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInstanceOf(Collection::class, $parser->getTransactions());
    }

    /**
     * Get sample file contents.
     *
     * @param string $file
     *
     * @return string
     */
    private function getSampleFileContents(string $file): string
    {
        return \file_get_contents(\realpath(__DIR__) . '/data/' . $file);
    }
}
