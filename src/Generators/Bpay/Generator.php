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
    public function __construct(?Header $header = null, ?array $transactions = null, ?Trailer $trailer)
    {
        $this->header = $header;
        $this->transactions = $transactions;
        $this->trailer = $trailer;

        $this->generate();
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
        $this->validateLineLengths();

        // Validate header attributes
        if ($this->header) {
            $this->validateAttributes($this->header, [
                'customerShortName' => static::VALIDATION_RULE_ALPHA,
                'processingDate' => static::VALIDATION_RULE_DATE,
            ]);

            $this->contents .= $this->header->getAttributesAsLine() . PHP_EOL;
        }

        //  validate transactions attributes
        if ($this->transactions) {
            /** @var Transaction $transaction */
            foreach ($this->transactions as $transaction) {
                $this->validateAttributes($transaction, [
                    'billerCode' => static::VALIDATION_RULE_NUMERIC,
                    'paymentAccountBSB' => static::VALIDATION_RULE_NUMERIC,
                    'paymentAccountNumber' => static::VALIDATION_RULE_NUMERIC,
                    'customerReferenceNumber' => static::VALIDATION_RULE_ALPHA,
                    'amount' => static::VALIDATION_RULE_NUMERIC,
                ]);

                $this->contents .= $transaction->getAttributesAsLine() . PHP_EOL;
            }
        }

        if ($this->trailer) {
            $this->validateAttributes($this->trailer, [
                'totalNumberOfPayments' => static::VALIDATION_RULE_NUMERIC,
                'totalFileValue' => static::VALIDATION_RULE_NUMERIC
            ]);

            $this->contents .= $this->trailer->getAttributesAsLine();
        }
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
     * Check if record length is no more than LINE_LENGTH
     *
     * @return void
     *
     * @throws LengthExceedsException
     */
    protected function validateLineLengths(): void
    {
        // validate header length
        if ($this->header) {
            $this->checkLineLength($this->header->getAttributesAsLine());
        }

        // validate transaction lengths
        if ($this->transactions) {
            foreach ($this->transactions as $transaction) {
                /** @var Transaction $transaction */
                $this->checkLineLength($transaction->getAttributesAsLine());
            }
        }

        if ($this->trailer) {
            $this->checkLineLength($this->trailer->getAttributesAsLine());
        }
    }
}
