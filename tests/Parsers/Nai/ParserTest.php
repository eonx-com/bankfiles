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

        self::assertIsFloat($formatAmount->invokeArgs($trait, ['100000']));
        self::assertSame((float)100, $formatAmount->invokeArgs($trait, ['10000']));
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
        self::assertSame('BNZA', $file->getHeader()->getReceiverId());
        self::assertCount(1, $parser->getGroups());
        self::assertCount(4, $parser->getAccounts());
        self::assertCount(6, $parser->getTransactions());
        self::assertCount(2, $parser->getErrors());

        $transactions = $parser->getTransactions();

        self::assertSame('NEW MULTI TFRDEBIT 5148       PYMT-ID 00000000 492672', $transactions[5]->getText());
    }

    /**
     * Parser should parse sample file successfully.
     * This tests a file which has slashes in the transaction records.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength) Method is long because of expected array.
     */
    public function testParserParsesSuccessfullyWhenFileHasTransactionWithSlashes(): void
    {
        $parser = new Parser($this->getSampleFileContents('nab_sample.NAI'));

        self::assertInstanceOf(File::class, $parser->getFile());
        /** @var \EoneoPay\BankFiles\Parsers\Nai\Results\File $file */
        $file = $parser->getFile();
        self::assertSame('BNZA', $file->getHeader()->getReceiverId());
        self::assertCount(1, $parser->getGroups());
        self::assertCount(4, $parser->getAccounts());
        self::assertCount(10, $parser->getTransactions());
        // one error line, 16,475,330/ .. because its missing required fundType
        self::assertCount(1, $parser->getErrors());

        $transactions = $parser->getTransactions();

        $expectedTransactions = [
            [
                'amount' => '64598',
                'code' => '16',
                'fundsType' => '0',
                'referenceNumber' => '0',
                'text' => 'ABC DEF',
                'transactionCode' => '936'
            ],
            [
                'amount' => '70050',
                'code' => '16',
                'fundsType' => '0',
                'referenceNumber' => '0005607',
                'text' => '',
                'transactionCode' => '475'
            ],
            [
                'amount' => '22410',
                'code' => '16',
                'fundsType' => '0',
                'referenceNumber' => '0005712',
                'text' => '',
                'transactionCode' => '475'
            ],
            [
                'amount' => '22650',
                'code' => '16',
                'fundsType' => '0',
                'referenceNumber' => '0005820',
                'text' => '',
                'transactionCode' => '475'
            ],
            [
                'amount' => '210620',
                'code' => '16',
                'fundsType' => '0',
                'referenceNumber' => '0005924',
                'text' => '',
                'transactionCode' => '475'
            ],
            [
                'amount' => '379200',
                'code' => '16',
                'fundsType' => '0',
                'referenceNumber' => '0005956',
                'text' => '',
                'transactionCode' => '475'
            ],
            [
                'amount' => '61915',
                'code' => '16',
                'fundsType' => '0',
                'referenceNumber' => '0005968',
                'text' => '',
                'transactionCode' => '475'
            ],
            [
                'amount' => '3300000',
                'code' => '16',
                'fundsType' => '0',
                'referenceNumber' => '0006100',
                'text' => '',
                'transactionCode' => '475'
            ],
            [
                'amount' => '330',
                'code' => '16',
                'fundsType' => '0',
                'referenceNumber' => '',
                'text' => '',
                'transactionCode' => '475'
            ],
            [
                'amount' => '104410',
                'code' => '16',
                'fundsType' => '0',
                'referenceNumber' => '0',
                'text' => 'AP8YA0436912      GEDFH            083310',
                'transactionCode' => '501'
            ]
        ];

        $actualTransactions = [];
        foreach ($transactions as $transaction) {
            $actualTransactions[] = [
                'amount' => $transaction->getAmount(),
                'code' => $transaction->getCode(),
                'fundsType' => $transaction->getFundsType(),
                'referenceNumber' => $transaction->getReferenceNumber(),
                'text' => $transaction->getText(),
                'transactionCode' => $transaction->getTransactionCode()
            ];
        }

        self::assertSame($expectedTransactions, $actualTransactions);
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

    public function testTrickyFile(): void
    {
        $parser = new Parser($this->getSampleFileContents('tricky.NAI'));
        $expected = 'STUART SMALL        FRKXMT8BK5          FRKXMT8BK5 Stuart Sm';

        self::assertCount(1, $parser->getTransactions());
        self::assertEquals($expected, $parser->getTransactions()[0]->getText());
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
        return \file_get_contents(\realpath(__DIR__) . '/data/' . $file) ?: '';
    }
}
