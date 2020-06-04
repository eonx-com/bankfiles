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
     */
    public function testGetDataAsExpected(): void
    {
        $data = [
            'code' => '03',
            'commercialAccountNumber' => 'account-number',
            'currencyCode' => 'AUD',
            'transactionCodes' => [],
        ];

        $identifier = new Identifier($data);

        self::assertSame($data['code'], $identifier->getCode());
        self::assertSame($data['commercialAccountNumber'], $identifier->getCommercialAccountNumber());
        self::assertSame($data['currencyCode'], $identifier->getCurrencyCode());
        self::assertSame($data['transactionCodes'], $identifier->getTransactionCodes());
    }
}
