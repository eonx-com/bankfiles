<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Aba;

use EoneoPay\BankFiles\Generators\Aba\Objects\DescriptiveRecord;
use EoneoPay\BankFiles\Generators\Aba\Objects\FileTotalRecord;
use EoneoPay\BankFiles\Generators\Aba\Objects\Transaction;
use EoneoPay\BankFiles\Generators\BaseGenerator;
use EoneoPay\BankFiles\Generators\Exceptions\LengthExceedsException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException;

class Generator extends BaseGenerator
{
    /**
     * @var DescriptiveRecord
     */
    private $descriptiveRecord;

    /**
     * @var FileTotalRecord
     */
    private $fileTotalRecord;

    /**
     * @var array|null
     */
    private $transactions;

    /**
     * Generator constructor.
     *
     * @param DescriptiveRecord $descriptiveRecord
     * @param array|null $transactions
     * @param FileTotalRecord $fileTotalRecord
     *
     * @throws LengthExceedsException
     * @throws ValidationFailedException
     * @throws ValidationNotAnObjectException
     */
    public function __construct(
        DescriptiveRecord $descriptiveRecord = null,
        ?array $transactions = null,
        ?FileTotalRecord $fileTotalRecord = null
    ) {
        $this->descriptiveRecord = $descriptiveRecord;
        $this->transactions = $transactions ?? [];
        $this->fileTotalRecord = $fileTotalRecord;
    }

    /**
     * Generate content
     *
     * @return void
     *
     * @throws LengthExceedsException
     * @throws ValidationFailedException
     * @throws ValidationNotAnObjectException
     */
    protected function generate(): void
    {
        $objects = [$this->descriptiveRecord];

        $creditTotal = 0;
        $debitTotal = 0;
        foreach ($this->transactions as $transaction) {
            /** @var Transaction $transaction */
            $objects[] = $transaction;

            if (Transaction::CODE_GENERAL_CREDIT === (int) $transaction->getTransactionCode()) {
                $creditTotal += (int) $transaction->getAmount();
            }
            if (Transaction::CODE_GENERAL_DEBIT === (int) $transaction->getTransactionCode()) {
                $debitTotal += (int) $transaction->getAmount();
            }
        }

        $objects[] = $this->fileTotalRecord ?? $this->createFileTotalRecord(
                \count($this->transactions),
                $creditTotal,
                $debitTotal
            );

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
        return new FileTotalRecord([
            'fileUserCountOfRecordsType' => $count,
            'fileUserCreditTotalAmount' => $creditTotal,
            'fileUserDebitTotalAmount' => $debitTotal,
            'fileUserNetTotalAmount' => $creditTotal - $debitTotal
        ]);
    }
}
