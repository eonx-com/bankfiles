<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Results\Account;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Identifier;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Trailer;
use EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext;
use Mockery\MockInterface;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\AbstractNaiResult
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\Account
 */
class AccountTest extends TestCase
{
    /**
     * Result should return data as expected.
     *
     * @return void
     */
    public function testGetDataAsExpected(): void
    {
        $data = [
            'group' => 1,
            'identifier' => new Identifier(),
            'index' => 2,
            'trailer' => new Trailer()
        ];

        $setExpectations = static function (MockInterface $context) use ($data): void {
            $context
                ->shouldReceive('getGroup')
                ->once()
                ->withArgs([$data['group']])
                ->andReturn(null);
            $context
                ->shouldReceive('getTransactionsForAccount')
                ->once()
                ->withArgs([$data['index']])
                ->andReturn([]);
        };

        /** @var \EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext $context */
        $context = $this->getMockWithExpectations(ResultsContext::class, $setExpectations);

        $account = new Account($context, $data);

        self::assertInstanceOf(Identifier::class, $account->getIdentifier());
        self::assertNull($account->getGroup());
        self::assertIsArray($account->getTransactions());
        self::assertInstanceOf(Trailer::class, $account->getTrailer());
    }
}
