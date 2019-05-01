<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai;

use EoneoPay\BankFiles\Parsers\AbstractLineByLineParser;
use EoneoPay\BankFiles\Parsers\Nai\Results\File;
use EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext;
use EoneoPay\Utils\Str;

class Parser extends AbstractLineByLineParser
{
    /**
     * @const string
     */
    private const ACCOUNT_IDENTIFIER = '03';

    /**
     * @const string
     */
    private const ACCOUNT_TRAILER = '49';

    /**
     * @const string
     */
    private const CONTINUATION = '88';

    /**
     * @const string
     */
    private const FILE_HEADER = '01';

    /**
     * @const string
     */
    private const FILE_TRAILER = '99';

    /**
     * @const string
     */
    private const GROUP_HEADER = '02';

    /**
     * @const string
     */
    private const GROUP_TRAILER = '98';

    /**
     * @const string
     */
    private const TRANSACTION_DETAIL = '16';

    /**
     * @var mixed[]
     */
    private $accounts = [];

    /**
     * @var int|null
     */
    private $currentAccount;

    /**
     * @var int
     */
    private $currentGroup;

    /**
     * @var int
     */
    private $currentLineNumber;

    /**
     * @var int|null
     */
    private $currentTransaction;

    /**
     * @var mixed[]
     */
    private $errors = [];

    /**
     * @var mixed[]
     */
    private $file = [];

    /**
     * @var mixed[]
     */
    private $groups = [];

    /**
     * @var string
     */
    private $previousCode;

    /**
     * @var bool
     */
    private $previousFull = true;

    /**
     * @var \EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext
     */
    private $resultsContext;

    /**
     * @var mixed[]
     */
    private $transactions = [];

    /**
     * Get accounts.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Account[]
     */
    public function getAccounts(): array
    {
        return $this->resultsContext->getAccounts();
    }

    /**
     * Get errors.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Error[]
     */
    public function getErrors(): array
    {
        return $this->resultsContext->getErrors();
    }

    /**
     * Get file.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\File|null
     */
    public function getFile(): ?File
    {
        return $this->resultsContext->getFile();
    }

    /**
     * Get groups.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Group[]
     */
    public function getGroups(): array
    {
        return $this->resultsContext->getGroups();
    }

    /**
     * Get transactions.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->resultsContext->getTransactions();
    }

    /**
     * Parse given contents and instantiate results context.
     *
     * @return void
     */
    protected function process(): void
    {
        parent::process();

        $this->resultsContext = new ResultsContext(
            $this->accounts,
            $this->errors,
            $this->file,
            $this->groups,
            $this->transactions
        );
    }

    /**
     * Process line and parse data
     *
     * @param int $lineNumber
     * @param string $line
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity) Inherited from file complexity
     */
    protected function processLine(int $lineNumber, string $line): void
    {
        $code = \substr($line, 0, 2);

        // Set current line number
        $this->currentLineNumber = $lineNumber;

        // If current code not valid, create error and skip to next line
        if ($this->isCodeValid($code) === false) {
            $this->addError($line);

            return;
        }

        $line = $this->sanitiseLine($line);

        // If continuation, update previous and skip to next line
        if ($code === self::CONTINUATION) {
            $this->continuePrevious($line);

            return;
        }

        // Current code becomes then previous one for next continuation
        $this->previousCode = $code;

        switch ($code) {
            case self::ACCOUNT_IDENTIFIER:
                $this->currentAccount = ($this->currentAccount ?? 0) + 1;
                $this->addAccountIdentifier($this->currentAccount, $line);
                break;
            case self::ACCOUNT_TRAILER:
                $this->addAccountTrailer($this->currentAccount ?? 0, $line);
                break;
            case self::FILE_HEADER:
                $this->file['header'] = $this->setItem($line);
                break;
            case self::FILE_TRAILER:
                $this->file['trailer'] = $this->setItem($line);
                break;
            case self::GROUP_HEADER:
                $this->currentAccount = null; // Reset current account for new group
                $this->currentGroup = \count($this->groups) + 1;
                $this->addGroupHeader($this->currentGroup, $line);
                break;
            case self::GROUP_TRAILER:
                $this->addGroupTrailer($this->currentGroup ?? 0, $line);
                break;
            case self::TRANSACTION_DETAIL:
                $this->currentTransaction = ($this->currentTransaction ?? 0) + 1;
                $this->addTransaction($line);
                break;
        }
    }

    /**
     * Add header to given account.
     *
     * @param int $account
     * @param string $identifier
     *
     * @return void
     */
    private function addAccountIdentifier(int $account, string $identifier): void
    {
        // If current group is null, it means that the file structure is wrong so error
        if ($this->currentGroup === null) {
            $this->addError($identifier);

            return;
        }

        if (isset($this->accounts[$account]) === false) {
            $this->accounts[$account] = ['group' => $this->currentGroup];
        }

        $this->accounts[$account]['identifier'] = $this->setItem($identifier);
    }

    /**
     * Add trailer to given account.
     *
     * @param int $account
     * @param string $trailer
     *
     * @return void
     */
    private function addAccountTrailer(int $account, string $trailer): void
    {
        // If account not already created it means that the file structure is wrong
        if (isset($this->accounts[$account]) === false) {
            $this->addError($trailer);

            return;
        }

        $this->accounts[$account]['trailer'] = $this->setItem($trailer);
    }

    /**
     * Add error.
     *
     * @param string $line
     *
     * @return void
     */
    private function addError(string $line): void
    {
        $this->errors[] = $this->setItem($line);
    }

    /**
     * Add header to given group.
     *
     * @param int $group
     * @param string $header
     *
     * @return void
     */
    private function addGroupHeader(int $group, string $header): void
    {
        if (isset($this->groups[$group]) === false) {
            $this->groups[$group] = [];
        }

        $this->groups[$group]['header'] = $this->setItem($header);
    }

    /**
     * Add trailer to given group.
     *
     * @param int $group
     * @param string $trailer
     *
     * @return void
     */
    private function addGroupTrailer(int $group, string $trailer): void
    {
        // If group not already created it means that the file structure is wrong
        if (isset($this->groups[$group]) === false) {
            $this->addError($trailer);

            return;
        }

        $this->groups[$group]['trailer'] = $this->setItem($trailer);
    }

    /**
     * Add transaction.
     *
     * @param string $transaction
     *
     * @return void
     */
    private function addTransaction(string $transaction): void
    {
        // If current account is null, it means that the file structure is wrong so error
        if ($this->currentAccount === null) {
            $this->addError($transaction);

            return;
        }

        $this->transactions[$this->currentTransaction] = [
            'account' => $this->currentAccount,
            'line' => $transaction,
            'line_number' => $this->currentLineNumber
        ];
    }

    /**
     * Continue account line for given index.
     *
     * @param string $index
     * @param string $line
     *
     * @return void
     */
    private function continueAccount(string $index, string $line): void
    {
        if (isset($this->accounts[$this->currentAccount][$index]['line']) === false) {
            $this->addError($line);

            return;
        }

        $this->accounts[$this->currentAccount][$index]['line'] .= $line;
    }

    /**
     * Continue file line for given index.
     *
     * @param string $index
     * @param string $line
     *
     * @return void
     */
    private function continueFile(string $index, string $line): void
    {
        $this->file[$index]['line'] .= $line;
    }

    /**
     * Continue group line for given index.
     *
     * @param string $index
     * @param string $line
     *
     * @return void
     */
    private function continueGroup(string $index, string $line): void
    {
        if (isset($this->groups[$this->currentGroup][$index]['line']) === false) {
            $this->addError($line);

            return;
        }

        $this->groups[$this->currentGroup][$index]['line'] .= $line;
    }

    /**
     * Continue previous line.
     *
     * @param string $line
     *
     * @return void
     */
    private function continuePrevious(string $line): void
    {
        // Remove 88, from the current line
        $line = (string)\substr($line, 3);
        // Add coma at the start of the line if previous record fits completely on the line
        if ($this->previousFull) {
            $line = ',' . $line;
        }

        switch ($this->previousCode) {
            case self::ACCOUNT_IDENTIFIER:
                $this->continueAccount('identifier', $line);
                break;
            case self::ACCOUNT_TRAILER:
                $this->continueAccount('trailer', $line);
                break;
            case self::FILE_HEADER:
                $this->continueFile('header', $line);
                break;
            case self::FILE_TRAILER:
                $this->continueFile('trailer', $line);
                break;
            case self::GROUP_HEADER:
                $this->continueGroup('header', $line);
                break;
            case self::GROUP_TRAILER:
                $this->continueGroup('trailer', $line);
                break;
            case self::TRANSACTION_DETAIL:
                $this->continueTransaction($line);
                break;
        }
    }

    /**
     * Continue transaction line.
     *
     * @param string $line
     *
     * @return void
     */
    private function continueTransaction(string $line): void
    {
        if (isset($this->transactions[$this->currentTransaction]['line']) === false) {
            $this->addError($line);

            return;
        }

        $this->transactions[$this->currentTransaction]['line'] .= $line;
    }

    /**
     * Check if given code is valid.
     *
     * @param string $code
     *
     * @return bool
     */
    private function isCodeValid(string $code): bool
    {
        $codes = [
            self::ACCOUNT_IDENTIFIER,
            self::ACCOUNT_TRAILER,
            self::CONTINUATION,
            self::FILE_HEADER,
            self::FILE_TRAILER,
            self::GROUP_HEADER,
            self::GROUP_TRAILER,
            self::TRANSACTION_DETAIL
        ];

        return \in_array($code, $codes, true);
    }

    /**
     * Check if record fits completely and remove slash.
     *
     * @param string $line
     *
     * @return string
     */
    private function sanitiseLine(string $line): string
    {
        // Determine if record fits completely on the line
        $this->previousFull = (new Str())->endsWith($line, '/');

        // Remove slash add the end of the line
        return \str_replace('/', '', $line);
    }

    /**
     * Structure item content with line number.
     *
     * @param string $line
     *
     * @return mixed[]
     */
    private function setItem(string $line): array
    {
        return ['line' => $line, 'line_number' => $this->currentLineNumber];
    }
}
