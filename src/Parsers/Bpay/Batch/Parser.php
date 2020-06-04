<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Batch;

use EoneoPay\BankFiles\Parsers\AbstractLineByLineParser;
use EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Error;
use EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Header;
use EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Trailer;
use EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Transaction;
use EoneoPay\Utils\Collection;
use EoneoPay\Utils\Interfaces\CollectionInterface;

class Parser extends AbstractLineByLineParser
{
    /**
     * @const string
     */
    private const HEADER = '1';

    /**
     * @const string
     */
    private const TRAILER = '9';

    /**
     * @const string
     */
    private const TRANSACTION = '2';

    /**
     * @var \EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Error[]
     */
    protected $errors;

    /**
     * @var \EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Header
     */
    protected $header;

    /**
     * @var \EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Trailer
     */
    protected $trailer;

    /**
     * @var \EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Transaction[]
     */
    protected $transactions;

    /**
     * @return \EoneoPay\Utils\Interfaces\CollectionInterface<\EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Error>
     */
    public function getErrors(): CollectionInterface
    {
        return new Collection($this->errors);
    }

    /**
     * Return the Header object
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * Return the Trailer object
     */
    public function getTrailer(): Trailer
    {
        return $this->trailer;
    }

    /**
     * @return \EoneoPay\Utils\Interfaces\CollectionInterface<\EoneoPay\BankFiles\Parsers\Bpay\Batch\Results\Transaction>
     */
    public function getTransactions(): CollectionInterface
    {
        return new Collection($this->transactions);
    }

    /**
     * Process line and parse data
     */
    protected function processLine(int $lineNumber, string $line): void
    {
        $code = $line[0] ?? '0';

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
                $this->errors[] = new Error(\compact('line', 'lineNumber'));
                break;
        }
    }

    /**
     * Parse header
     */
    private function processHeader(string $line): Header
    {
        return new Header([
            'customerId' => \substr($line, 1, 16),
            'customerShortName' => \substr($line, 17, 20),
            'processingDate' => \substr($line, 37, 8),
            'restOfRecord' => \substr($line, 45, 174),
        ]);
    }

    /**
     * Parse trailer
     */
    private function processTrailer(string $line): Trailer
    {
        return new Trailer([
            'numberOfApprovals' => \substr($line, 1, 10),
            'amountOfApprovals' => \substr($line, 11, 13),
            'numberOfDeclines' => \substr($line, 24, 10),
            'amountOfDeclines' => \substr($line, 34, 13),
            'numberOfPayments' => \substr($line, 47, 10),
            'amountOfPayments' => \substr($line, 57, 13),
            'restOfRecord' => \substr($line, 70, 149),
        ]);
    }

    /**
     * Parse transaction items
     */
    private function processTransaction(string $line): Transaction
    {
        return new Transaction([
            'billerCode' => \substr($line, 1, 10),
            'accountBsb' => \substr($line, 11, 6),
            'accountNumber' => \substr($line, 17, 9),
            'customerReferenceNumber' => \substr($line, 26, 20),
            'amount' => \substr($line, 46, 13),
            'reference1' => \substr($line, 59, 10),
            'reference2' => \substr($line, 69, 20),
            'reference3' => \substr($line, 89, 50),
            'returnCode' => \substr($line, 139, 4),
            'returnCodeDescription' => \substr($line, 143, 50),
            'transactionReferenceNumber' => \substr($line, 193, 21),
            'restOfRecord' => \substr($line, 214, 5),
        ]);
    }
}
