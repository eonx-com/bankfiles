<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Results\GroupTrailer;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class GroupTrailerTest extends TestCase
{
    /**
     * Should return total in float data type
     *
     * @group Nai-Group-Trailer
     *
     * @return void
     */
    public function testShouldReturnFloatTotal(): void
    {
        $value = '125055';
        $expected = '1250.55';

        $groupTrailer = new GroupTrailer([
            'groupControlTotalA' => $value,
            'groupControlTotalB' => $value
        ]);

        /** @noinspection UnnecessaryAssertionInspection Assertion neecessary for exact instance type */
        self::assertInternalType('float', $groupTrailer->getGroupControlTotalA());
        self::assertEquals($expected, $groupTrailer->getGroupControlTotalA());

        /** @noinspection UnnecessaryAssertionInspection Assertion neecessary for exact instance type */
        self::assertInternalType('float', $groupTrailer->getGroupControlTotalB());
        self::assertEquals($expected, $groupTrailer->getGroupControlTotalB());
    }
}
