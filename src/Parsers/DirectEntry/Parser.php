<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\DirectEntry;

use EoneoPay\BankFiles\Parsers\AbstractLineByLineParser;
use EoneoPay\BankFiles\Parsers\DirectEntry\Results\Header;
use EoneoPay\BankFiles\Parsers\DirectEntry\Results\Trailer;
use EoneoPay\BankFiles\Parsers\DirectEntry\Results\Transaction;
use EoneoPay\Utils\Collection;
use EoneoPay\Utils\Interfaces\CollectionInterface;

class Parser extends AbstractLineByLineParser
{
    /**
     * @const Code for header line
     */
    private const HEADER = 0;

    /**
     * @const Code for trailer line
     */
    private const TRAILER = 7;

    /**
     * @const Code for transaction
     */
    private const TRANSACTION_1 = 1;

    /**
     * @const Code for transaction
     */
    private const TRANSACTION_2 = 2;

    /**
     * @var \EoneoPay\BankFiles\Parsers\DirectEntry\Results\Header
     */
    private $header;

    /**
     * @var \EoneoPay\BankFiles\Parsers\DirectEntry\Results\Trailer
     */
    private $trailer;

    /**
     * @var \EoneoPay\BankFiles\Parsers\DirectEntry\Results\Transaction[]
     */
    private $transactions;

    /**
     * Get header record
     *
     * @return \EoneoPay\BankFiles\Parsers\DirectEntry\Results\Header
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * Get trailer record
     *
     * @return \EoneoPay\BankFiles\Parsers\DirectEntry\Results\Trailer
     */
    public function getTrailer(): Trailer
    {
        return $this->trailer;
    }

    /**
     * Get transactions from the file
     *
     * @return \EoneoPay\Utils\Interfaces\CollectionInterface
     */
    public function getTransactions(): CollectionInterface
    {
        return new Collection($this->transactions);
    }

    /**
     * {@inheritdoc}
     */
    protected function processLine(int $lineNumber, string $line): void
    {
        // code is the first character in line
        $code = $line[0];

        switch ($code) {
            case self::HEADER:
                $this->header = $this->processHeader($line);
                break;
            case self::TRAILER:
                $this->trailer = $this->processTrailer($line);
                break;
            case self::TRANSACTION_1:
            case self::TRANSACTION_2:
                $this->transactions[] = $this->processTransaction($line);
                break;
        }
    }

    /**
     * Process header block of line
     *
     * @param string $line
     *
     * @return \EoneoPay\BankFiles\Parsers\DirectEntry\Results\Header
     */
    private function processHeader(string $line): Header
    {
        return new Header([
            'dateProcessed' => \substr($line, 74, 6),
            'description' => \substr($line, 62, 12),
            'userFinancialInstitution' => \substr($line, 20, 3),
            'userIdSupplyingFile' => \substr($line, 56, 6),
            'userSupplyingFile' => \substr($line, 30, 26),
            'reelSequenceNumber' => \substr($line, 18, 2)
        ]);
    }

    /**
     * Process trailer block of line
     *
     * @param string $line
     *
     * @return \EoneoPay\BankFiles\Parsers\DirectEntry\Results\Trailer
     */
    private function processTrailer(string $line): Trailer
    {
        return new Trailer([
            'bsb' => \substr($line, 1, 7),
            'numberPayments' => \substr($line, 74, 6),
            'totalNetAmount' => \substr($line, 20, 10),
            'totalCreditAmount' => \substr($line, 30, 10),
            'totalDebitAmount' => \substr($line, 40, 10)
        ]);
    }

    /**
     * Process transaction block of line
     *
     * @param string $line
     *
     * @return \EoneoPay\BankFiles\Parsers\DirectEntry\Results\Transaction
     */
    private function processTransaction(string $line): Transaction
    {
        return new Transaction([
            'accountName' => \substr($line, 30, 32),
            'accountNumber' => \substr($line, 8, 9),
            'amount' => \substr($line, 20, 10),
            'bsb' => \substr($line, 1, 7),
            'indicator' => $line[17] ?? '',
            'lodgmentReference' => \substr($line, 62, 18),
            'recordType' => $line[0] ?? '',
            'remitterName' => \substr($line, 96, 16),
            'traceAccountNumber' => \substr($line, 87, 9),
            'traceBsb' => \substr($line, 80, 7),
            'txnCode' => \substr($line, 18, 2),
            'withholdingTax' => \substr($line, 112, 8)
        ]);
    }
}
