<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Bpay;

use EoneoPay\BankFiles\Generators\BaseGenerator;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Header;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Trailer;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Transaction;
use EoneoPay\BankFiles\Generators\Exceptions\LengthExceedsException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException;

class Generator extends BaseGenerator
{
    /**
     * @var Header
     */
    private $header;

    /**
     * @var Trailer|null
     */
    private $trailer;

    /**
     * @var array|null
     */
    private $transactions;

    /**
     * Generator constructor.
     *
     * @param Header $header
     * @param array|null $transactions
     * @param Trailer|null $trailer
     *
     * @throws LengthExceedsException
     * @throws ValidationFailedException
     * @throws ValidationNotAnObjectException
     */
    public function __construct(Header $header, ?array $transactions = null, ?Trailer $trailer = null)
    {
        $this->header = $header;
        $this->transactions = $transactions ?? [];
        $this->trailer = $trailer;
    }

    /**
     * Generate
     *
     * @return void
     *
     * @throws LengthExceedsException
     * @throws ValidationFailedException
     * @throws ValidationNotAnObjectException
     */
    protected function generate(): void
    {
        $objects = [$this->header];

        $totalAmount = 0;
        foreach ($this->transactions as $transaction) {
            /** @var Transaction $transaction */
            $objects[] = $transaction;
            $totalAmount += (int) $transaction->getAmount();
        }

        $objects[] = $this->trailer ?? $this->createTrailer(\count($this->transactions), $totalAmount);

        $this->writeLinesForObjects($objects);
    }

    /**
     * Return the defined line length of a generator
     *
     * @return int
     */
    protected function getLineLength(): int
    {
        return 144;
    }

    /**
     * Create new trailer.
     *
     * @param int $count
     * @param int $totalAmount
     *
     * @return \EoneoPay\BankFiles\Generators\Bpay\Objects\Trailer
     */
    private function createTrailer(int $count, int $totalAmount): Trailer
    {
        return new Trailer([
            'totalNumberOfPayments' => $count,
            'totalFileValue' => $totalAmount
        ]);
    }
}
