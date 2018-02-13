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
    const DIRECT_ENTRY_CREDIT = 0;
    const DIRECT_ENTRY_DEBIT = 1;

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
        ?DescriptiveRecord $descriptiveRecord = null,
        ?array $transactions = [],
        ?FileTotalRecord $fileTotalRecord = null
    ) {
        $this->descriptiveRecord = $descriptiveRecord;
        $this->transactions = $transactions;
        $this->fileTotalRecord = $fileTotalRecord;

        $this->generate();
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
        $this->validateLineLengths();

        // Validate descriptive record's attributes
        if ($this->descriptiveRecord) {
            $this->validateAttributes($this->descriptiveRecord, [
                'nameOfUseSupplyingFile' => static::VALIDATION_RULE_ALPHA,
                'numberOfUseSupplyingFile' => static::VALIDATION_RULE_NUMERIC,
                'descriptionOfEntries' => static::VALIDATION_RULE_ALPHA,
                'dateToBeProcessed' => static::VALIDATION_RULE_DATE,
            ]);

            $this->contents .= $this->descriptiveRecord->getAttributesAsLine() . PHP_EOL;
        }

        //  validate transactions attributes
        if ($this->transactions) {
            /** @var Transaction $transaction */
            foreach ($this->transactions as $transaction) {
                $this->validateAttributes($transaction, [
                    'bsbNumber' => static::VALIDATION_RULE_BSB,
                    'accountNumberToBeCreditedDebited' => static::VALIDATION_RULE_ALPHA,
                    'amount' => static::VALIDATION_RULE_NUMERIC,
                    'titleOfAccountToBeCreditedDebited' => static::VALIDATION_RULE_ALPHA,
                    'lodgementReference' => static::VALIDATION_RULE_ALPHA,
                    'traceRecord' => static::VALIDATION_RULE_BSB,
                    'accountNumber' => static::VALIDATION_RULE_ALPHA,
                    'nameOfRemitter' => static::VALIDATION_RULE_ALPHA,
                    'amountOfWithholdingTax' => static::VALIDATION_RULE_NUMERIC,
                ]);

                $this->contents .= $transaction->getAttributesAsLine() . PHP_EOL;
            }
        }

        // validate file total record attributes
        if ($this->fileTotalRecord) {
            $this->validateAttributes($this->fileTotalRecord, [
                'fileUserNetTotalAmount' => static::VALIDATION_RULE_NUMERIC,
                'fileUserCreditTotalAmount' => static::VALIDATION_RULE_NUMERIC,
                'fileUserDebitTotalAmount' => static::VALIDATION_RULE_NUMERIC,
                'fileUserCountOfRecordsType' => static::VALIDATION_RULE_NUMERIC,
            ]);

            $this->contents .= $this->fileTotalRecord->getAttributesAsLine();
        }
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
     * Check if record length is no more than 120 characters
     *
     * @return void
     *
     * @throws LengthExceedsException
     */
    protected function validateLineLengths(): void
    {
        // validate descriptive record length
        if ($this->descriptiveRecord) {
            $this->checkLineLength($this->descriptiveRecord->getAttributesAsLine());
        }

        // validate transaction lengths
        if ($this->transactions) {
            foreach ($this->transactions as $transaction) {
                /** @var Transaction $transaction */
                $this->checkLineLength($transaction->getAttributesAsLine());
            }
        }

        // validate file total record length
        if ($this->fileTotalRecord) {
            $this->checkLineLength($this->fileTotalRecord->getAttributesAsLine());
        }
    }
}
