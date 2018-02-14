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
    public function __construct(?Header $header = null, ?array $transactions = null, ?Trailer $trailer = null)
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
            $this->validateAttributes($this->header, $this->header->getValidationRules());

            $this->contents .= $this->header->getAttributesAsLine() . PHP_EOL;
        }

        //  validate transactions attributes
        if ($this->transactions) {
            foreach ($this->transactions as $transaction) {
                /** @var Transaction $transaction */
                $this->validateAttributes($transaction, $transaction->getValidationRules());

                $this->contents .= $transaction->getAttributesAsLine() . PHP_EOL;
            }
        }

        if ($this->trailer) {
            $this->validateAttributes($this->trailer, $this->trailer->getValidationRules());

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
        // Validate header length
        if ($this->header) {
            $this->checkLineLength($this->header->getAttributesAsLine());
        }

        // Validate transaction lengths
        if ($this->transactions) {
            foreach ($this->transactions as $transaction) {
                /** @var Transaction $transaction */
                $this->checkLineLength($transaction->getAttributesAsLine());
            }
        }

        // Validate trailer length
        if ($this->trailer) {
            $this->checkLineLength($this->trailer->getAttributesAsLine());
        }
    }
}
