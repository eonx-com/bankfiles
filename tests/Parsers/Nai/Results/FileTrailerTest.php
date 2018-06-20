<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Results\FileTrailer;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class FileTrailerTest extends TestCase
{
    /**
     * Should total as float data type
     *
     * @group Nai-File-Trailer
     *
     * @return void
     */
    public function testShould(): void
    {
        $fileControlTotalA = '255016';
        $totalA = 2550.16;

        $fileControlTotalB = '35000';
        $totalB = 350.00;

        $fileTrailer = new FileTrailer([
            'fileControlTotalA' => $fileControlTotalA,
            'fileControlTotalB' => $fileControlTotalB,
            'numberOfGroups' => 3
        ]);

        self::assertEquals(3, $fileTrailer->getNumberOfGroups());

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInternalType('float', $fileTrailer->getFileControlTotalA());
        self::assertEquals($totalA, $fileTrailer->getFileControlTotalA());

        /** @noinspection UnnecessaryAssertionInspection Assertion necessary for exact instance type */
        self::assertInternalType('float', $fileTrailer->getFileControlTotalB());
        self::assertEquals($totalB, $fileTrailer->getFileControlTotalB());
    }
}
