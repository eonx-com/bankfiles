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
        $trans1 = $this->createTransaction();
        $trans1
            ->setAttribute('billerCode', '11133')
            ->setAttribute('amount', '200');
        $trans2 = $this->createTransaction();

        $generator = new Generator($header, [$trans1, $trans2]);

        self::assertContains($header->getAttributesAsLine(), $generator->getContents());
        self::assertContains($trans1->getAttributesAsLine(), $generator->getContents());
    }
}
