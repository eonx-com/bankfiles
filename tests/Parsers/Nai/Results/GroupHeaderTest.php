<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use DateTime;
use EoneoPay\BankFiles\Parsers\Nai\Parser;
use EoneoPay\BankFiles\Parsers\Nai\Results\GroupHeader;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class GroupHeaderTest extends TestCase
{
    /**
     * Should return file header and get an attribute value
     *
     * @group Nai-Group-Header
     *
     * @return void
     */
    public function testShouldReturnAnAttributeValue(): void
    {
        $filename = \realpath(__DIR__ . '/../data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);
        $fileHeader = $parser->getFileHeader();

        self::assertNotNull($fileHeader->getReceiverId());
    }

    /**
     * Should return asOfDate as DateTime object
     *
     * @group Nai-Group-Header
     *
     * @return void
     */
    public function testShouldReturnAsOfDate(): void
    {
        $date = '160121';

        $groupHeader = new GroupHeader([
            'asOfDate' => $date
        ]);

        self::assertInstanceOf(DateTime::class, $groupHeader->getAsOfDate());
        self::assertEquals(new DateTime($date), $groupHeader->getAsOfDate());
    }

    /**
     * Should set and return group header attribute value
     *
     * @group Nai-Group-Header
     *
     * @return void
     */
    public function testShouldSetAndReturnAnAttribute(): void
    {
        $expected = 'TEST';

        $groupHeader = new GroupHeader([
            'ultimateReceiverId' => $expected
        ]);

        self::assertEquals($expected, $groupHeader->getUltimateReceiverId());
    }
}
