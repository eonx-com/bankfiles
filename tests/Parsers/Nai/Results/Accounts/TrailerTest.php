<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results\Accounts;

use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Trailer;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Trailer
 */
class TrailerTest extends TestCase
{
    /**
     * Result should return data as expected.
     *
     * @return void
     */
    public function testGetDataAsExpected(): void
    {
        $data = [
            'code' => '03',
            'accountControlTotalA' => '10000',
            'accountControlTotalB' => '10000'
        ];

        $trailer = new Trailer($data);

        self::assertSame($data['code'], $trailer->getCode());
        self::assertSame((float)100, $trailer->getAccountControlTotalA());
        self::assertSame((float)100, $trailer->getAccountControlTotalB());
    }
}
