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
     * Get value from a string at position
     *
     * @param string $line
     * @param int $start
     * @param int $length
     *
     * @return string
     */
    private function getValue(string $line, int $start, int $length): string
    {
        return \trim(\substr($line, $start, $length) ?: '');
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
            'dateProcessed' => $this->getValue($line, 74, 6),
            'description' => $this->getValue($line, 62, 12),
            'userFinancialInstitution' => $this->getValue($line, 20, 3),
            'userIdSupplyingFile' => $this->getValue($line, 56, 6),
            'userSupplyingFile' => $this->getValue($line, 30, 26),
            'reelSequenceNumber' => $this->getValue($line, 18, 2)
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
            'bsb' => $this->getValue($line, 1, 7),
            'numberPayments' => $this->getValue($line, 74, 6),
            'totalNetAmount' => $this->getValue($line, 20, 10),
            'totalCreditAmount' => $this->getValue($line, 30, 10),
            'totalDebitAmount' => $this->getValue($line, 40, 10)
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
            'accountName' => $this->getValue($line, 30, 32),
            'accountNumber' => $this->getValue($line, 8, 9),
            'amount' => $this->getValue($line, 20, 10),
            'bsb' => $this->getValue($line, 1, 7),
            'indicator' => $this->getValue($line, 17, 1),
            'lodgmentReference' => $this->getValue($line, 62, 18),
            'recordType' => $this->getValue($line, 0, 1),
            'remitterName' => $this->getValue($line, 96, 16),
            'traceAccountNumber' => $this->getValue($line, 87, 9),
            'traceBsb' => $this->getValue($line, 80, 7),
            'txnCode' => $this->getValue($line, 18, 2),
            'withholdingTax' => $this->getValue($line, 112, 8)
        ]);
    }
}
