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
     */
    public function testGetDataAsExpected(): void
    {
        $data = [
            'code' => '03',
            'fileControlTotalA' => '10000',
            'fileControlTotalB' => '10000',
            'numberOfGroups' => '3',
            'numberOfRecords' => '4',
        ];

        $trailer = new Trailer($data);

        self::assertSame($data['code'], $trailer->getCode());
        self::assertSame((float)100, $trailer->getFileControlTotalA());
        self::assertSame((float)100, $trailer->getFileControlTotalB());
        self::assertSame($data['numberOfGroups'], $trailer->getNumberOfGroups());
        self::assertSame($data['numberOfRecords'], $trailer->getNumberOfRecords());
    }
}
