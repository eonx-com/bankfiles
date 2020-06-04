<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Bpay;

use EoneoPay\BankFiles\Generators\Bpay\Generator;
use EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException;
use EoneoPay\BankFiles\Generators\Interfaces\GeneratorInterface;

class GeneratorTest extends TestCase
{
    /**
     * Generator should throw exception when no transactions given.
     *
     * @return void
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException
     */
    public function testEmptyTransactionsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Generator($this->createHeader(), []);
    }

    /**
     * Generated data should be present in the content
     *
     * @group Generator-Bpay
     *
     * @return void
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException
     */
    public function testGeneratedDataShouldBeInTheContent(): void
    {
        $header = $this->createHeader();

        // create a transaction and set it's values
        $trans1 = $this->createTransaction();
        $trans1
            ->setAttribute('billerCode', '11133')
            ->setAttribute('amount', '200');
        $trans2 = $this->createTransaction();

        $generator = new Generator($header, [$trans1, $trans2]);

        self::assertStringContainsString($header->getAttributesAsLine(), $generator->getContents());
        self::assertStringContainsString($trans1->getAttributesAsLine(), $generator->getContents());
    }

    /**
     * Generator should throw exception when invalid transaction given.
     *
     * @return void
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException
     */
    public function testInvalidTransactionException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Generator($this->createHeader(), ['invalid']))
            ->setBreakLines(GeneratorInterface::BREAK_LINE_WINDOWS)
            ->getContents();
    }
}
