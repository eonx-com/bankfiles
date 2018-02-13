<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use DateTime;
use EoneoPay\BankFiles\Parsers\Nai\Results\FileHeader;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class FileHeaderTest extends TestCase
{
    /**
     * Should return file creation date as DateTime object
     *
     * @group Nai-File-Header
     *
     * @return void
     */
    public function testShouldReturnFileCreationDate(): void
    {
        $date = '160122';
        $expected = DateTime::createFromFormat('ymd', $date);
        $expected->setTime(0, 0);

        $fileHeader = new FileHeader([
            'fileCreationDate' => $date
        ]);

        self::assertInstanceOf(DateTime::class, $fileHeader->getFileCreationDate());
        self::assertEquals($expected, $fileHeader->getFileCreationDate());
    }
}
