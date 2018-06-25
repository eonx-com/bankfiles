<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results\Groups;

use EoneoPay\BankFiles\Parsers\Nai\Results\Groups\Header;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\Groups\Header
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
            'asOfDate' => '180625',
            'asOfTime' => '0000',
            'code' => '02',
            'groupStatus' => '1',
            'originatorReceiverId' => 'original-receiver-id',
            'ultimateReceiverId' => 'ultimate-receiver-id'
        ];

        $header = new Header($data);

        self::assertEquals($data['asOfDate'], $header->getAsOfDate());
        self::assertEquals($data['asOfTime'], $header->getAsOfTime());
        self::assertEquals($data['code'], $header->getCode());
        self::assertEquals($data['groupStatus'], $header->getGroupStatus());
        self::assertEquals($data['originatorReceiverId'], $header->getOriginatorReceiverId());
        self::assertEquals($data['ultimateReceiverId'], $header->getUltimateReceiverId());
    }
}
