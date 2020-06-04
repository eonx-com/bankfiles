<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Bpay;

use EoneoPay\BankFiles\Generators\BaseGenerator;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Header;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Trailer;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Transaction;
use EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException;

class Generator extends BaseGenerator
{
    /**
     * @var \EoneoPay\BankFiles\Generators\Bpay\Objects\Header
     */
    private $header;

    /**
     * @var \EoneoPay\BankFiles\Generators\Bpay\Objects\Trailer|null
     */
    private $trailer;

    /**
     * @var mixed[]
     */
    private $transactions;

    /**
     * Generator constructor.
     *
     * @param mixed[] $transactions
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException
     */
    public function __construct(Header $header, array $transactions, ?Trailer $trailer = null)
    {
        if (\count($transactions) === 0) {
            throw new InvalidArgumentException('No transactions provided.');
        }

        $this->header = $header;
        $this->transactions = $transactions;
        $this->trailer = $trailer;
    }

    /**
     * Generate
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\LengthMismatchesException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException
     */
    protected function generate(): void
    {
        $objects = [$this->header];
        $totalAmount = 0;

        // Ensure transactions is always an array
        $transactions = $this->transactions;

        foreach ($transactions as $transaction) {
            if (($transaction instanceof Transaction) === false) {
                throw new InvalidArgumentException(\sprintf(
                    'Transaction must be %s, %s given.',
                    Transaction::class,
                    \gettype($transaction)
                ));
            }

            $objects[] = $transaction;
            $totalAmount += (int)$transaction->getAmount();
        }

        $objects[] = $this->trailer ?? $this->createTrailer(\count($objects) - 1, $totalAmount);

        $this->writeLinesForObjects($objects);
    }

    /**
     * Return the defined line length of a generator
     */
    protected function getLineLength(): int
    {
        return 144;
    }

    /**
     * Create new trailer.
     */
    private function createTrailer(int $count, int $totalAmount): Trailer
    {
        return new Trailer([
            'totalNumberOfPayments' => $count,
            'totalFileValue' => $totalAmount,
        ]);
    }
}
