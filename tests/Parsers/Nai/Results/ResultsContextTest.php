<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext
 */
class ResultsContextTest extends TestCase
{
    /**
     * Context should return default/empty value when no data set.
     *
     * @return void
     */
    public function testEmptyGettersReturnValueAsExpected(): void
    {
        $context = new ResultsContext([], [], [], [], []);

        self::assertNull($context->getAccount(0));
        self::assertInternalType('array', $context->getAccounts());
        self::assertInternalType('array', $context->getAccountsForGroup(1));
        self::assertInternalType('array', $context->getErrors());
        self::assertNull($context->getFile());
        self::assertNull($context->getGroup(0));
        self::assertInternalType('array', $context->getGroups());
        self::assertInternalType('array', $context->getTransactions());
        self::assertInternalType('array', $context->getTransactionsForAccount(1));
    }

    /**
     * Context should create errors as expected.
     *
     * @return void
     */
    public function testErrorsInRecords(): void
    {
        $accounts = [
            [
                'identifier' => ['line' => '', 'line_number' => 1],
                'group' => 1,
                'trailer' => ['line' => '', 'line_number' => 2]
            ],
            []
        ];
        $groups = [[]];
        $transactions = [
            ['line' => '', 'line_number' => 2]
        ];

        $context = new ResultsContext($accounts, [], [], $groups, $transactions);

        self::assertCount(4, $context->getErrors());
    }
}
