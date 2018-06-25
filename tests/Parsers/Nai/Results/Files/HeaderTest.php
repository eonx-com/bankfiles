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
     *
     * @return void
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
            'senderId' => 'sender-id'
        ];

        $header = new Header($data);

        self::assertEquals($data['blockingFactor'], $header->getBlockingFactor());
        self::assertEquals($data['code'], $header->getCode());
        self::assertEquals($data['fileCreationDate'], $header->getFileCreationDate());
        self::assertEquals($data['fileCreationTime'], $header->getFileCreationTime());
        self::assertEquals($data['fileSequenceNumber'], $header->getFileSequenceNumber());
        self::assertEquals($data['physicalRecordLength'], $header->getPhysicalRecordLength());
        self::assertEquals($data['receiverId'], $header->getReceiverId());
        self::assertEquals($data['senderId'], $header->getSenderId());
    }
}
