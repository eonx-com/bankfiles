<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Bpay;

use EoneoPay\BankFiles\Generators\Bpay\Generator;

class GeneratorTest extends TestCase
{
    /**
     * Generated data should be present in the content
     *
     * @group Generator-Bpay
     *
     * @return void
     */
    public function testGeneratedDataShouldBeInTheContent(): void
    {
        $header = $this->createHeader();

        // create a transaction and set it's values
        $trans1 =  $this->createTransaction();
        $trans1
            ->setAttribute('billerCode', \str_pad('11133', 10, '0', STR_PAD_LEFT))
            ->setAttribute('amount', \str_pad('200', 13, '0', STR_PAD_LEFT));
        $trans2 =  $this->createTransaction();

        $trailer = $this->createTrailer();

        $generator = new Generator($header, [$trans1, $trans2], $trailer);

        self::assertContains($header->getAttributesAsLine(), $generator->getContents());
        self::assertContains($trans1->getAttributesAsLine(), $generator->getContents());
        self::assertContains($trailer->getAttributesAsLine(), $generator->getContents());
    }
}
