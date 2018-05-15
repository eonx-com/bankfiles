<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Aba;

use EoneoPay\BankFiles\Generators\Aba\Objects\DescriptiveRecord;
use EoneoPay\BankFiles\Generators\Aba\Objects\FileTotalRecord;
use EoneoPay\BankFiles\Generators\Aba\Objects\Transaction;
use EoneoPay\BankFiles\Generators\BaseGenerator;

class Generator extends BaseGenerator
{
    /**
     * @var \EoneoPay\BankFiles\Generators\Aba\Objects\DescriptiveRecord|null
     */
    private $descriptiveRecord;

    /**
     * @var \EoneoPay\BankFiles\Generators\Aba\Objects\FileTotalRecord|null
     */
    private $fileTotalRecord;

    /**
     * @var mixed[]|null
     */
    private $transactions;

    /**
     * Generator constructor.
     *
     * @param \EoneoPay\BankFiles\Generators\Aba\Objects\DescriptiveRecord $descriptiveRecord
     * @param mixed[]|null $transactions
     * @param \EoneoPay\BankFiles\Generators\Aba\Objects\FileTotalRecord $fileTotalRecord
     */
    public function __construct(
        ?DescriptiveRecord $descriptiveRecord = null,
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
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\LengthMismatchesException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException
     */
    protected function generate(): void
    {
        $objects = [$this->descriptiveRecord];

        $creditTotal = 0;
        $debitTotal = 0;

        // Cast transactions to array
        $transactions = (array)$this->transactions;

        foreach ($transactions as $transaction) {
            /** @var \EoneoPay\BankFiles\Generators\Aba\Objects\Transaction $transaction */
            $objects[] = $transaction;

            if ((int)$transaction->getTransactionCode() === Transaction::CODE_GENERAL_CREDIT) {
                $creditTotal += (int)$transaction->getAmount();
            }
            if ((int)$transaction->getTransactionCode() === Transaction::CODE_GENERAL_DEBIT) {
                $debitTotal += (int)$transaction->getAmount();
            }
        }

        $objects[] = $this->fileTotalRecord ?? $this->createFileTotalRecord(
            \count($transactions),
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
