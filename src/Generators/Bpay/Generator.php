<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Bpay;

use EoneoPay\BankFiles\Generators\BaseGenerator;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Header;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Trailer;

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
     * @var mixed[]|null
     */
    private $transactions;

    /**
     * Generator constructor.
     *
     * @param \EoneoPay\BankFiles\Generators\Bpay\Objects\Header $header
     * @param mixed[]|null $transactions
     * @param \EoneoPay\BankFiles\Generators\Bpay\Objects\Trailer|null $trailer
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
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\LengthMismatchesException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException
     */
    protected function generate(): void
    {
        $objects = [$this->header];
        $totalAmount = 0;

        // Ensure transactions is always an array
        $transactions = (array)$this->transactions;

        foreach ($transactions as $transaction) {
            /** @var \EoneoPay\BankFiles\Generators\Bpay\Objects\Transaction $transaction */
            $objects[] = $transaction;
            $totalAmount += (int)$transaction->getAmount();
        }

        $objects[] = $this->trailer ?? $this->createTrailer(\count($transactions), $totalAmount);

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
