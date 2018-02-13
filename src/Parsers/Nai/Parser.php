<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai;

use EoneoPay\BankFiles\Parsers\AbstractLineByLineParser;
use EoneoPay\BankFiles\Parsers\Nai\Results\Account;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Identifier;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Trailer;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Transaction;
use EoneoPay\BankFiles\Parsers\Nai\Results\Error;
use EoneoPay\BankFiles\Parsers\Nai\Results\FileHeader;
use EoneoPay\BankFiles\Parsers\Nai\Results\FileTrailer;
use EoneoPay\BankFiles\Parsers\Nai\Results\GroupHeader;
use EoneoPay\BankFiles\Parsers\Nai\Results\GroupTrailer;
use Illuminate\Support\Collection;

class Parser extends AbstractLineByLineParser
{
    use AccountSummaryCodes;

    use TransactionDetailCodes;

    const ACCOUNT_IDENTIFIER = '03';
    const ACCOUNT_TRAILER = '49';
    const CONTINUATION = '88';
    const FILE_HEADER = '01';
    const FILE_TRAILER = '99';
    const GROUP_HEADER = '02';
    const GROUP_TRAILER = '98';
    const TRANSACTION_DETAIL = '16';

    /** @var Collection $accountBlocks */
    private $accountBlocks;

    /**@var array $accounts */
    private $accounts = [];

    /** @var Error $errors */
    private $errors = [];

    /** @var FileHeader $fileHeader */
    private $fileHeader;

    /** @var array $fileHeaderContents */
    private $fileHeaderContents = [];

    /** @var FileTrailer $groupTrailer */
    private $fileTrailer;

    /** @var array $fileTrailerContents */
    private $fileTrailerContents;

    /** @var GroupHeader $groupHeader */
    private $groupHeader;

    /** @var array $groupHeaderContents */
    private $groupHeaderContents = [];

    /** @var GroupTrailer $groupTrailer */
    private $groupTrailer;

    /** @var array $groupTrailerContents */
    private $groupTrailerContents;

    /** @var string $previousCode */
    private $previousCode;

    /** @var Collection $transactions */
    private $transactions;

    /**
     * Format account identifier transactions and add code summary
     *
     * @param array $transactionCodes
     *
     * @return array
     */
    public function formatTransactionCodes(array $transactionCodes): array
    {
        foreach ($transactionCodes as $key => [$code, $amount]) {
            $transactionCodes[$key] = [
                'code' => $code,
                'description' => $this->getCodeSummary($code),
                'amount' => $amount,
            ];
        }

        return $transactionCodes;
    }

    /**
     * Return account blocks
     *
     * @return Collection
     */
    public function getAccounts(): Collection
    {
        return \collect($this->accountBlocks);
    }

    /**
     * Return errors
     *
     * @return Collection
     */
    public function getErrors(): Collection
    {
        return \collect($this->errors);
    }

    /**
     * Return the file header
     *
     * @return FileHeader
     */
    public function getFileHeader(): FileHeader
    {
        return $this->fileHeader;
    }

    /**
     * Return file trailer
     *
     * @return FileTrailer
     */
    public function getFileTrailer(): FileTrailer
    {
        return $this->fileTrailer;
    }

    /**
     * Return the group header
     *
     * @return GroupHeader
     */
    public function getGroupHeader(): GroupHeader
    {
        return $this->groupHeader;
    }

    /**
     * Return group trailer
     *
     * @return GroupTrailer
     */
    public function getGroupTrailer(): GroupTrailer
    {
        return $this->groupTrailer;
    }

    /**
     * Return the collection of transactions
     *
     * @return Collection
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * Process line and parse data
     *
     * @param string $line
     *
     * @return void
     */
    public function processLine(string $line): void
    {
        $line = $this->sanitiseLine($line);

        $code = \substr($line, 0, 2);

        switch ($code) {
            case self::CONTINUATION:
                $this->process88($line);
                break;
            case self::FILE_HEADER:
                $this->previousCode = self::FILE_HEADER;
                $this->fileHeaderContents[] = $line;
                break;
            case self::GROUP_HEADER:
                $this->previousCode = self::GROUP_HEADER;
                $this->groupHeaderContents[] = $line;
                break;
            case self::ACCOUNT_IDENTIFIER:
                $this->previousCode = self::ACCOUNT_IDENTIFIER;
                $this->accounts[] = $line;
                break;
            case self::TRANSACTION_DETAIL:
                $this->previousCode = self::TRANSACTION_DETAIL;
                $this->accounts[] = $line;
                break;
            case self::ACCOUNT_TRAILER:
                $this->previousCode = self::ACCOUNT_TRAILER;
                $this->accounts[] = $line;
                break;
            case self::GROUP_TRAILER:
                $this->previousCode = self::GROUP_TRAILER;
                $this->groupTrailerContents[] = $line;
                break;
            case self::FILE_TRAILER:
                $this->previousCode = self::FILE_TRAILER;
                $this->fileTrailerContents[] = $line;
                break;
            default:
                $this->previousCode = null;
                $this->errors[] = new Error([
                    'line' => $line
                ]);
                break;
        }
    }

    /**
     * Override Process method
     *
     * @return void
     */
    protected function process(): void
    {
        parent::process();

        $this->parseFileHeader()
            ->parseGroupHeader()
            ->parseAccounts()
            ->extractTransactions()
            ->parseGroupTrailer()
            ->parseFileTrailer();
    }

    /**
     * Extract all transactions from all of the accounts
     *  and add its respective Account as an attribute
     *
     * @return self
     */
    private function extractTransactions(): self
    {
        $this->transactions = \collect($this->accountBlocks)->flatMap(function ($account) {
            /** @var Account $account */
            $transactions = $account->getTransactions();

            return $transactions->map(function ($transaction, $key) use ($account) {
                /** @var Transaction $transaction */
                return $transaction->setAccount($account);
            });
        });

        return $this;
    }

    /**
     * Parse account trailer
     *
     * @param $accountTrailer
     *
     * @return Trailer
     */
    private function parseAccountTrailer($accountTrailer): Trailer
    {
        [
            $code,
            $accountControlTotalA,
            $accountControlTotalB
        ] = \explode(',', $accountTrailer);

        return new Trailer(\compact('code', 'accountControlTotalA', 'accountControlTotalB'));
    }

    /**
     * Parse accounts
     *
     * @return self
     */
    private function parseAccounts(): self
    {
        /**
         * We know that account block is bounded by code 03 and 49
         * Let's group them into array of blocks
         */

        $start = 0;
        foreach ($this->accounts as $key => $line) {
            $code = \substr($line, 0, 2);

            if ($code === self::ACCOUNT_IDENTIFIER) {
                $start = $key;
            }

            /**
             * Slice the arrays bounded by code 03 and 49
             * Put it in an array as a block
             */
            if ($code === self::ACCOUNT_TRAILER) {
                $block = \array_slice($this->accounts, $start, ($key + 1) - $start);

                /**
                 * Now let's loop through the block and separate into 3 parts.
                 * 1. (03) Account identification and summary status
                 * 2. (16) Transaction detail
                 * 3. (49) Account trailer
                 */
                $accountIdentifier = null;
                $transactionDetails = [];
                $accountTrailer = null;
                $prevCode = null;
                foreach ($block as $item) {
                    $code = \substr($item, 0, 2);

                    switch ($code) {
                        case self::ACCOUNT_IDENTIFIER:
                            $prevCode = self::ACCOUNT_IDENTIFIER;
                            $accountIdentifier .= $item;
                            break;

                        case self::TRANSACTION_DETAIL:
                            $prevCode = self::TRANSACTION_DETAIL;

                            // Sometimes there are multiple transactions so let's put them in array
                            $transactionDetails[] = $item;
                            break;

                        case self::ACCOUNT_TRAILER:
                            $prevCode = self::ACCOUNT_TRAILER;
                            $accountTrailer .= $item;
                            break;

                        default: // If no matching code, it means it's a continuation of the previous line
                            if ($prevCode === self::ACCOUNT_IDENTIFIER) {
                                $accountIdentifier .= $item;
                            }

                            if ($prevCode === self::TRANSACTION_DETAIL) {
                                $transactionDetails[count($transactionDetails) - 1] .= \substr($item, 1);
                            }

                            break;
                    }
                }

                $this->accountBlocks[] = new Account([
                    'identifier' => $this->parseIdentifier($accountIdentifier),
                    'transactions' => $this->parseTransaction($transactionDetails),
                    'trailer' => $this->parseAccountTrailer($accountTrailer)
                ]);
            }
        }

        return $this;
    }

    /**
     * Parse file header data
     *
     * @return self
     */
    private function parseFileHeader(): self
    {
        $line = \implode('', \array_values($this->fileHeaderContents));

        [
            $code,
            $senderId,
            $receiverId,
            $fileCreationDate,
            $fileCreationTime,
            $fileSequenceNumber,
            $physicalRecordLength,
            $blockingFactor
        ] = \explode(',', $line);

        $this->fileHeader = new FileHeader(\compact(
            'code',
            'senderId',
            'receiverId',
            'fileCreationDate',
            'fileCreationTime',
            'fileSequenceNumber',
            'physicalRecordLength',
            'blockingFactor'
        ));

        return $this;
    }

    /**
     * Parse file trailer
     *
     * @return self
     */
    private function parseFileTrailer(): self
    {
        $data = \implode('', $this->fileTrailerContents);

        [
            $code,
            $fileControlTotalA,
            $numberOfGroups,
            $numberOfRecords,
            $fileControlTotalB
        ] = \explode(',', $data);

        $this->fileTrailer = new FileTrailer(\compact(
            'code',
            'fileControlTotalA',
            'numberOfGroups',
            'numberOfRecords',
            'fileControlTotalB'
        ));

        return $this;
    }

    /**
     * Parse group header data
     *
     * @return self
     */
    private function parseGroupHeader(): self
    {
        $line = \implode('', \array_values($this->groupHeaderContents));

        [
            $code,
            $ultimateReceiverId,
            $originatorReceiverId,
            $groupStatus,
            $asOfDate,
            $asOfTime,
        ] = \explode(',', $line);

        $this->groupHeader = new GroupHeader(\compact(
            'code',
            'ultimateReceiverId',
            'originatorReceiverId',
            'groupStatus',
            'asOfDate',
            'asOfTime'
        ));

        return $this;
    }

    /**
     * Parse the group trailer
     *
     * @return self
     */
    private function parseGroupTrailer(): self
    {
        $data = \implode('', $this->groupTrailerContents);

        [
            $code,
            $groupControlTotalA,
            $numberOfAccounts,
            $groupControlTotalB
        ] = \explode(',', $data);

        $this->groupTrailer = new GroupTrailer(\compact(
            'code',
            'groupControlTotalA',
            'numberOfAccounts',
            'groupControlTotalB'
        ));

        return $this;
    }

    /**
     * Parse account identifier
     *
     * @param $identifier
     *
     * @return Identifier
     */
    private function parseIdentifier($identifier): Identifier
    {
        $data = \explode(',', $identifier);

        // Get the first 3 elements
        [$code, $commercialAccountNumber, $currencyCode] = $data;

        /*
         * So from 4th item onwards are Transaction code and Amount
         * We can group them in pairs [transactionCode, Amount]
         *
         * But first let remove the first 3 elements
        */
        $transactionCodes = \array_slice($data, 3);
        $transactionCodes = $this->formatTransactionCodes(\array_chunk($transactionCodes, 2));

        return new Identifier([
            'code' => $code,
            'commercialAccountNumber' => $commercialAccountNumber,
            'currencyCode' => $currencyCode,
            'transactionCodes' => $transactionCodes
        ]);
    }

    /**
     * Parse transaction details
     *
     * @param $transactionDetails
     *
     * @return array
     */
    private function parseTransaction($transactionDetails): array
    {
        $transactions = [];
        foreach ($transactionDetails as $key => $transaction) {
            /*
             * Since explode will always return 6 elements
             * lets use PHP 7.1 symmetric array destructuring
             */
            [
                $code,
                $transactionCode,
                $amount,
                $fundsType,
                $referenceNumber,
                $text
            ] = \explode(',', $transaction);

            $transactions[] = new Transaction([
                'code' => $code,
                'transactionCode' => $transactionCode,
                'transactionDetails' => $this->getTransactionCodeDetails($transactionCode),
                'amount' => $amount,
                'fundsType' => $fundsType,
                'referenceNumber' => $referenceNumber,
                'text' => $text
            ]);
        }

        return $transactions;
    }

    /**
     * Parse code 88
     *
     * @param string $line
     *
     * @return void
     */
    private function process88(string $line): void
    {
        $line = \substr($line, 2);

        switch ($this->previousCode) {
            case self::FILE_HEADER:
                $this->fileHeaderContents[] = $line;
                break;
            case self::GROUP_HEADER:
                $this->groupHeaderContents[] = $line;
                break;
            case self::ACCOUNT_IDENTIFIER:
            case self::TRANSACTION_DETAIL:
            case self::ACCOUNT_TRAILER:
                $this->accounts[] .= $line;
                break;
            case self::GROUP_TRAILER:
                $this->groupTrailerContents[] = $line;
                break;
            case self::FILE_TRAILER:
                $this->fileTrailerContents[] = $line;
                break;
        }
    }

    /**
     * Remove '/', leading and trailing spaces
     *
     * @param string $line
     *
     * @return string
     */
    private function sanitiseLine(string $line): string
    {
        return \str_replace('/', '', \trim($line));
    }
}
