<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results\Transactions;

use EoneoPay\BankFiles\Parsers\Nai\Results\Transactions\Details;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\Transactions\Details
 */
class DetailsTest extends TestCase
{
    /**
     * Result should return data as expected.
     */
    public function testGetDataAsExpected(): void
    {
        $data = [
            'description' => 'description',
            'particulars' => 'particulars',
            'type' => 'type',
        ];

        $details = new Details($data);

        self::assertSame($data['description'], $details->getDescription());
        self::assertSame($data['particulars'], $details->getParticulars());
        self::assertSame($data['type'], $details->getType());
    }
}
