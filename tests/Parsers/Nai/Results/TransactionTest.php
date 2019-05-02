<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext;
use EoneoPay\BankFiles\Parsers\Nai\Results\Transaction;
use EoneoPay\BankFiles\Parsers\Nai\Results\Transactions\Details;
use Mockery\MockInterface;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\Transaction
 */
class TransactionTest extends TestCase
{
    /**
     * Result should return data as expected.
     *
     * @return void
     */
    public function testGetDataAsExpected(): void
    {
        $data = [
            'account' => 1,
            'amount' => '12300',
            'code' => '16',
            'fundsType' => 'funds-type',
            'referenceNumber' => 'reference-number',
            'text' => 'text',
            'transactionCode' => '23',
            'transactionDetails' => new Details()
        ];

        $setExpectations = static function (MockInterface $context) use ($data): void {
            $context
                ->shouldReceive('getAccount')
                ->once()
                ->withArgs([$data['account']])
                ->andReturn(null);
        };

        /** @var \EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext $context */
        $context = $this->getMockWithExpectations(ResultsContext::class, $setExpectations);

        $transaction = new Transaction($context, $data);

        self::assertNull($transaction->getAccount());
        self::assertSame($data['amount'], $transaction->getAmount());
        self::assertSame($data['code'], $transaction->getCode());
        self::assertSame($data['fundsType'], $transaction->getFundsType());
        self::assertSame($data['referenceNumber'], $transaction->getReferenceNumber());
        self::assertSame($data['text'], $transaction->getText());
        self::assertSame($data['transactionCode'], $transaction->getTransactionCode());
        self::assertInstanceOf(Details::class, $transaction->getTransactionDetails());
    }
}
