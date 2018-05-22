<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Aba;

use EoneoPay\BankFiles\Generators\Aba\Objects\DescriptiveRecord;
use EoneoPay\BankFiles\Generators\Aba\Objects\FileTotalRecord;
use EoneoPay\BankFiles\Generators\Aba\Objects\Transaction;
use EoneoPay\BankFiles\Generators\BaseGenerator;
use EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException;

class Generator extends BaseGenerator
{
    /**
     * @var \EoneoPay\BankFiles\Generators\Aba\Objects\DescriptiveRecord
     */
    private $descriptiveRecord;

    /**
     * @var \EoneoPay\BankFiles\Generators\Aba\Objects\FileTotalRecord|null
     */
    private $fileTotalRecord;

    /**
     * @var mixed[]
     */
    private $transactions;

    /**
     * Generator constructor.
     *
     * @param \EoneoPay\BankFiles\Generators\Aba\Objects\DescriptiveRecord $descriptiveRecord
     * @param mixed[] $transactions
     * @param \EoneoPay\BankFiles\Generators\Aba\Objects\FileTotalRecord|null $fileTotalRecord
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException
     */
    public function __construct(
        DescriptiveRecord $descriptiveRecord,
        array $transactions,
        ?FileTotalRecord $fileTotalRecord = null
    ) {
        if (empty($transactions)) {
            throw new InvalidArgumentException('No transactions provided.');
        }

        $this->descriptiveRecord = $descriptiveRecord;
        $this->transactions = $transactions;
        $this->fileTotalRecord = $fileTotalRecord;
    }

    /**
     * Generate content
     *
     * @return void
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\LengthMismatchesException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException
     */
    protected function generate(): void
    {
        $objects = [$this->descriptiveRecord];

        $creditTotal = 0;
        $debitTotal = 0;

        foreach ($this->transactions as $transaction) {
            if (($transaction instanceof Transaction) === false) {
                throw new InvalidArgumentException(\sprintf(
                    'Transaction must be %s, %s given.',
                    Transaction::class,
                    \gettype($transaction)
                ));
            }

            $objects[] = $transaction;

            if ((int)$transaction->getTransactionCode() === Transaction::CODE_GENERAL_CREDIT) {
                $creditTotal += (int)$transaction->getAmount();
            }
            if ((int)$transaction->getTransactionCode() === Transaction::CODE_GENERAL_DEBIT) {
                $debitTotal += (int)$transaction->getAmount();
            }
        }

        $objects[] = $this->createFileTotalRecord(\count($objects) - 1, $creditTotal, $debitTotal);

        $this->writeLinesForObjects($objects);
    }

    /**
     * Return the defined line length of a generator
     *
     * @return int
     */
    protected function getLineLength(): int
    {
        return 120;
    }

    /**
     * Create new file total record.
     *
     * @param int $count
     * @param int $creditTotal
     * @param int $debitTotal
     *
     * @return \EoneoPay\BankFiles\Generators\Aba\Objects\FileTotalRecord
     */
    private function createFileTotalRecord(int $count, int $creditTotal, int $debitTotal): FileTotalRecord
    {
        if ($this->fileTotalRecord !== null) {
            return $this->fileTotalRecord;
        }

        return $this->fileTotalRecord = new FileTotalRecord([
            'fileUserCountOfRecordsType' => $count,
            'fileUserCreditTotalAmount' => $creditTotal,
            'fileUserDebitTotalAmount' => $debitTotal,
            'fileUserNetTotalAmount' => $creditTotal - $debitTotal
        ]);
    }
}
