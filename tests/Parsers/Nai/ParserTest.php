<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai;

use EoneoPay\BankFiles\Parsers\Nai\ControlTotal;
use EoneoPay\BankFiles\Parsers\Nai\Parser;
use EoneoPay\BankFiles\Parsers\Nai\Results\File;
use EoneoPay\BankFiles\Parsers\Nai\TransactionDetailCodes;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext
 * @covers \EoneoPay\BankFiles\Parsers\Nai\AccountSummaryCodes
 * @covers \EoneoPay\BankFiles\Parsers\Nai\ControlTotal
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Parser
 * @covers \EoneoPay\BankFiles\Parsers\Nai\TransactionDetailCodes
 */
class ParserTest extends TestCase
{
    /**
     * ControlTotal should format amount as expected.
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function testControlTotalTraitReturnFormattedAmount(): void
    {
        /** @var \EoneoPay\BankFiles\Parsers\Nai\ControlTotal $trait */
        $trait = $this->getObjectForTrait(ControlTotal::class);
        $formatAmount = $this->getProtectedMethod(\get_class($trait), 'formatAmount');

        self::assertInternalType('float', $formatAmount->invokeArgs($trait, ['100000']));
        self::assertEquals((float)100, $formatAmount->invokeArgs($trait, ['10000']));
    }

    /**
     * Parser should handle structure errors as expected.
     *
     * @return void
     */
    public function testParserHandleStructureErrorAsExpected(): void
    {
        $parser = new Parser($this->getSampleFileContents('structure_errors.NAI'));

        self::assertCount(8, $parser->getErrors());
    }

    /**
     * Parser should parse sample file successfully.
     *
     * @return void
     */
    public function testParserParsesSuccessfully(): void
    {
        $parser = new Parser($this->getSampleFileContents('sample.NAI'));

        self::assertInstanceOf(File::class, $parser->getFile());
        /** @var \EoneoPay\BankFiles\Parsers\Nai\Results\File $file */
        $file = $parser->getFile();
        self::assertEquals('BNZA', $file->getHeader()->getReceiverId());
        self::assertCount(1, $parser->getGroups());
        self::assertCount(4, $parser->getAccounts());
        self::assertCount(5, $parser->getTransactions());
        self::assertCount(2, $parser->getErrors());
    }

    /**
     * Transaction codes detail trait should return null if code is invalid.
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function testTransactionCodesTraitReturnNullWhenInvalidCode(): void
    {
        /** @var \EoneoPay\BankFiles\Parsers\Nai\TransactionDetailCodes $trait */
        $trait = $this->getObjectForTrait(TransactionDetailCodes::class);

        self::assertNull($trait->getTransactionCodeDetails('invalid'));
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
