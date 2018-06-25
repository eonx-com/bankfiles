<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results\Accounts;

use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Identifier;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Identifier
 */
class IdentifierTest extends TestCase
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
            'commercialAccountNumber' => 'account-number',
            'currencyCode' => 'AUD',
            'transactionCodes' => []
        ];

        $identifier = new Identifier($data);

        self::assertEquals($data['code'], $identifier->getCode());
        self::assertEquals($data['commercialAccountNumber'], $identifier->getCommercialAccountNumber());
        self::assertEquals($data['currencyCode'], $identifier->getCurrencyCode());
        self::assertEquals($data['transactionCodes'], $identifier->getTransactionCodes());
    }
}
