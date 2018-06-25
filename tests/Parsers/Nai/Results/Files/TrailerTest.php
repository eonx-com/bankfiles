<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results\Files;

use EoneoPay\BankFiles\Parsers\Nai\Results\Files\Trailer;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\Files\Trailer
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
            'fileControlTotalA' => '10000',
            'fileControlTotalB' => '10000',
            'numberOfGroups' => '3',
            'numberOfRecords' => '4'
        ];

        $trailer = new Trailer($data);

        self::assertEquals($data['code'], $trailer->getCode());
        self::assertEquals((float)100, $trailer->getFileControlTotalA());
        self::assertEquals((float)100, $trailer->getFileControlTotalB());
        self::assertEquals($data['numberOfGroups'], $trailer->getNumberOfGroups());
        self::assertEquals($data['numberOfRecords'], $trailer->getNumberOfRecords());
    }
}
