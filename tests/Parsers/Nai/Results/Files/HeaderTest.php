<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results\Files;

use EoneoPay\BankFiles\Parsers\Nai\Results\Files\Header;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\Files\Header
 */
class HeaderTest extends TestCase
{
    /**
     * Result should return data as expected.
     */
    public function testGetDataAsExpected(): void
    {
        $data = [
            'blockingFactor' => '',
            'code' => '01',
            'fileCreationDate' => '180625',
            'fileCreationTime' => '0000',
            'fileSequenceNumber' => '1',
            'physicalRecordLength' => '182',
            'receiverId' => 'receiver-id',
            'senderId' => 'sender-id',
        ];

        $header = new Header($data);

        self::assertSame($data['blockingFactor'], $header->getBlockingFactor());
        self::assertSame($data['code'], $header->getCode());
        self::assertSame($data['fileCreationDate'], $header->getFileCreationDate());
        self::assertSame($data['fileCreationTime'], $header->getFileCreationTime());
        self::assertSame($data['fileSequenceNumber'], $header->getFileSequenceNumber());
        self::assertSame($data['physicalRecordLength'], $header->getPhysicalRecordLength());
        self::assertSame($data['receiverId'], $header->getReceiverId());
        self::assertSame($data['senderId'], $header->getSenderId());
    }
}
