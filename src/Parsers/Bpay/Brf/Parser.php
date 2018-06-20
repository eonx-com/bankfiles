<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Brf;

use EoneoPay\BankFiles\Parsers\AbstractLineByLineParser;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Error;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Header;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Trailer;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Transaction;
use EoneoPay\Utils\Collection;
use EoneoPay\Utils\Interfaces\CollectionInterface;

class Parser extends AbstractLineByLineParser
{
    private const HEADER = '00';
    private const TRAILER = '99';
    private const TRANSACTION = '50';

    /** @var mixed[] $errors */
    protected $errors;

    /** @var \EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Header $header */
    protected $header;

    /** @var \EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Trailer $trailer */
    protected $trailer;

    /** @var \EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Transaction[] $transactions */
    protected $transactions;

    /**
     * Return the Error object
     *
     * @return \EoneoPay\Utils\Interfaces\CollectionInterface
     */
    public function getErrors(): CollectionInterface
    {
        return new Collection($this->errors);
    }

    /**
     * Return the Header object
     *
     * @return \EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Header
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * Return the Trailer object
     *
     * @return \EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Trailer
     */
    public function getTrailer(): Trailer
    {
        return $this->trailer;
    }

    /**
     * Return the Transaction
     *
     * @return \EoneoPay\Utils\Interfaces\CollectionInterface
     */
    public function getTransactions(): CollectionInterface
    {
        return new Collection($this->transactions);
    }

    /**
     * Process line and parse data
     *
     * @param int $lineNumber
     * @param string $line
     *
     * @return void
     */
    protected function processLine(int $lineNumber, string $line): void
    {
        $code = \substr($line, 0, 2);

        switch ($code) {
            case self::HEADER:
                $this->header = $this->processHeader($line);
                break;

            case self::TRANSACTION:
                $this->transactions[] = $this->processTransaction($line);
                break;

            case self::TRAILER:
                $this->trailer = $this->processTrailer($line);
                break;

            default:
                $this->errors[] = new Error(['line' => $line, 'lineNumber' => $lineNumber]);
                break;
        }
    }

    /**
     * Parse header
     *
     * @param string $line
     *
     * @return \EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Header
     */
    private function processHeader(string $line): Header
    {
        return new Header([
            'billerCode' => \substr($line, 2, 10),
            'billerShortName' => \substr($line, 12, 20),
            'billerCreditBSB' => \substr($line, 32, 6),
            'billerCreditAccount' => \substr($line, 38, 9),
            'fileCreationDate' => \substr($line, 47, 8),
            'fileCreationTime' => \substr($line, 55, 6),
            'filler' => \substr($line, 61, 158)
        ]);
    }

    /**
     * Parse trailer
     *
     * @param string $line
     *
     * @return \EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Trailer
     */
    private function processTrailer(string $line): Trailer
    {
        return new Trailer([
            'billerCode' => \substr($line, 2, 10),
            'numberOfPayments' => \substr($line, 12, 9),
            'amountOfPayments' => \substr($line, 21, 15),
            'numberOfErrorCorrections' => \substr($line, 36, 9),
            'amountOfErrorCorrections' => \substr($line, 45, 15),
            'numberOfReversals' => \substr($line, 60, 9),
            'amountOfReversals' => \substr($line, 69, 15),
            'settlementAmount' => \substr($line, 84, 15),
            'filler' => \substr($line, 99, 120)
        ]);
    }

    /**
     * Parse transaction items
     *
     * @param string $line
     *
     * @return \EoneoPay\BankFiles\Parsers\Bpay\Brf\Results\Transaction
     */
    private function processTransaction(string $line): Transaction
    {
        return new Transaction([
            'billerCode' => \substr($line, 2, 10),
            'customerReferenceNumber' => \substr($line, 12, 20),
            'paymentInstructionType' => \substr($line, 32, 2),
            'transactionReferenceNumber' => \substr($line, 34, 21),
            'originalReferenceNumber' => \substr($line, 55, 21),
            'errorCorrectionReason' => \substr($line, 76, 3),
            'amount' => \substr($line, 79, 12),
            'paymentDate' => \substr($line, 91, 8),
            'paymentTime' => \substr($line, 99, 6),
            'settlementDate' => \substr($line, 105, 8),
            'filler' => \substr($line, 113, 106)
        ]);
    }
}
