<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\AccountSummaryCodes;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Identifier as AccountIdentifier;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Trailer as AccountTrailer;
use EoneoPay\BankFiles\Parsers\Nai\Results\Files\Header as FilerHeader;
use EoneoPay\BankFiles\Parsers\Nai\Results\Files\Trailer as FileTrailer;
use EoneoPay\BankFiles\Parsers\Nai\Results\Groups\Header as GroupHeader;
use EoneoPay\BankFiles\Parsers\Nai\Results\Groups\Trailer as GroupTrailer;
use EoneoPay\BankFiles\Parsers\Nai\TransactionDetailCodes;
use EoneoPay\Utils\Str;

class ResultsContext
{
    use AccountSummaryCodes;
    use TransactionDetailCodes;

    /**
     * @var \EoneoPay\BankFiles\Parsers\Nai\Results\Account[]
     */
    private $accounts = [];

    /**
     * @var mixed[]
     */
    private $caching = [];

    /**
     * @var \EoneoPay\BankFiles\Parsers\Nai\Results\Error[]
     */
    private $errors = [];

    /**
     * @var \EoneoPay\BankFiles\Parsers\Nai\Results\File
     */
    private $file;

    /**
     * @var \EoneoPay\BankFiles\Parsers\Nai\Results\Group[]
     */
    private $groups = [];

    /**
     * @var \EoneoPay\BankFiles\Parsers\Nai\Results\Transaction[]
     */
    private $transactions = [];

    /**
     * ResultsContext constructor.
     *
     * @param mixed[] $accounts
     * @param mixed[] $errors
     * @param mixed[] $file
     * @param mixed[] $groups
     * @param mixed[] $transactions
     */
    public function __construct(array $accounts, array $errors, array $file, array $groups, array $transactions)
    {
        $this
            ->initAccounts($accounts)
            ->initErrors($errors)
            ->initFile($file)
            ->initGroups($groups)
            ->initTransactions($transactions);
    }

    /**
     * Get account for given index.
     *
     * @param int $index
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Account|null
     */
    public function getAccount(int $index): ?Account
    {
        return $this->accounts[$index] ?? null;
    }

    /**
     * Get accounts.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Account[]
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    /**
     * Get accounts for given group.
     *
     * @param int $group
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Account[]
     */
    public function getAccountsForGroup(int $group): array
    {
        return $this->caching[\sprintf('group_%d_accounts', $group)] ?? [];
    }

    /**
     * Get errors.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get file.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * Get group for given index.
     *
     * @param int $index
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Group|null
     */
    public function getGroup(int $index): ?Group
    {
        return $this->groups[$index] ?? null;
    }

    /**
     * Get groups.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Group[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * Get transactions.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Get transactions for given account.
     *
     * @param int $account
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Transaction[]
     */
    public function getTransactionsForAccount(int $account): array
    {
        return $this->caching[\sprintf('account_%d_transactions', $account)] ?? [];
    }

    /**
     * Add error.
     *
     * @param string $line
     * @param int $lineNumber
     *
     * @return void
     */
    private function addError(string $line, int $lineNumber): void
    {
        $this->errors[] = new Error(\compact('line', 'lineNumber'));
    }

    /**
     * Cache account for given group.
     *
     * @param int $group
     * @param \EoneoPay\BankFiles\Parsers\Nai\Results\Account $account
     *
     * @return void
     */
    private function cacheAccount(int $group, Account $account): void
    {
        $this->cacheResult(\sprintf('group_%d_accounts', $group), $account);
    }

    /**
     * Cache result for given key.
     *
     * @param string $key
     * @param mixed $result
     *
     * @return void
     */
    private function cacheResult(string $key, $result): void
    {
        if (isset($this->caching[$key]) === false) {
            $this->caching[$key] = [];
        }

        $this->caching[$key][] = $result;
    }

    /**
     * Cache transaction for given account.
     *
     * @param int $account
     * @param \EoneoPay\BankFiles\Parsers\Nai\Results\Transaction $transaction
     *
     * @return void
     */
    private function cacheTransaction(int $account, Transaction $transaction): void
    {
        $this->cacheResult(\sprintf('account_%d_transactions', $account), $transaction);
    }

    /**
     * Format account identifier transactions and add code summary
     *
     * @param mixed[] $transactionCodes
     *
     * @return mixed[]
     */
    private function formatTransactionCodes(array $transactionCodes): array
    {
        foreach ($transactionCodes as $key => $codes) {
            [$code, $amount] = $codes;

            $transactionCodes[$key] = [
                'code' => $code,
                'description' => $this->getCodeSummary($code),
                'amount' => $amount
            ];
        }

        return $transactionCodes;
    }

    /**
     * Get data from line as an associative array using given attributes. If line structure invalid, return null.
     *
     * @param string[] $attributes
     * @param string $line
     * @param int $lineNumber
     *
     * @return mixed[]|null
     */
    private function getDataFromLine(array $attributes, string $line, int $lineNumber): ?array
    {
        $data = [];
        $lineArray = \explode(',', $line);

        foreach ($attributes as $index => $attribute) {
            // If one attribute is missing from the file, return null
            if (isset($lineArray[$index]) === false) {
                $this->addError($line, $lineNumber);

                return null;
            }

            $data[$attribute] = $lineArray[$index];
        }

        return $data;
    }

    /**
     * Get transaction data from line as an associative array using given attributes.
     * If line structure invalid, return null. If the last element in data is missing,
     * its alright as transaction might not have a text.
     *
     * @param string $line
     * @param int $lineNumber
     *
     * @return mixed[]|null
     */
    private function getTransactionDataFromLine(string $line, int $lineNumber): ?array
    {
        $required = ['code', 'transactionCode', 'amount', 'fundsType'];
        $optional = ['referenceNumber', 'text'];

        $data = [];
        $lineArray = \explode(',', $line);
        $str = new Str();

        $attributes = \array_merge($required, $optional);

        foreach ($attributes as $index => $attribute) {
            $value = $lineArray[$index] ?? '';
            $endsWithSlash = $str->endsWith((string)$value, '/');
            $data[$attribute] = $endsWithSlash ? \str_replace('/', '', $value) : $value;

            // If attribute ends with slash, it's the last one of line, exit
            if ($endsWithSlash) {
                break;
            }
        }

        // Validate all required and optional attributes are defined
        foreach ($attributes as $attribute) {
            if (isset($data[$attribute]) === true && $data[$attribute] !== '') {
                continue;
            }

            // if this is a required attribute fail and return.
            if (\in_array($attribute, $required, true) === true) {
                // Add error if data is either null or empty string
                $this->addError($line, $lineNumber);

                // stop processing this line.
                return null;
            }

            // otherwise set a default value to it.
            $data[$attribute] = '';
        }

        return $data;
    }

    /**
     * Instantiate account identifier.
     *
     * @param mixed[] $identifier
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Identifier|null
     */
    private function initAccountIdentifier(array $identifier): ?AccountIdentifier
    {
        $attributes = ['code', 'commercialAccountNumber', 'currencyCode'];
        $data = $this->getDataFromLine($attributes, $identifier['line'], $identifier['line_number']);

        if ($data === null) {
            $this->addError($identifier['line'], $identifier['line_number']);

            return null;
        }

        /**
         * So from 4th item onwards are Transaction code and Amount
         * We can group them in pairs [transactionCode, Amount]
         *
         * But first let remove the first 3 elements
         */
        $transactionCodes = \array_slice(\explode(',', $identifier['line']), 3);
        $transactionCodes = $this->formatTransactionCodes(\array_chunk($transactionCodes, 2));

        return new AccountIdentifier(\array_merge($data, ['transactionCodes' => $transactionCodes]));
    }

    /**
     * Instantiate account trailer.
     *
     * @param mixed[] $trailer
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Trailer|null
     */
    private function initAccountTrailer(array $trailer): ?AccountTrailer
    {
        return $this->instantiateSimpleItem([
            'code',
            'accountControlTotalA',
            'accountControlTotalB'
        ], AccountTrailer::class, $trailer);
    }

    /**
     * Instantiate accounts.
     *
     * @param mixed[] $accounts
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext
     */
    private function initAccounts(array $accounts): self
    {
        foreach ($accounts as $index => $account) {
            if (isset($account['identifier'], $account['trailer']) === false) {
                continue;
            }

            $accountResult = $this->instantiateNaiResult(Account::class, [
                'group' => $account['group'] - 1, // Indexes coming from parser start from 1, we want 0
                'identifier' => $this->initAccountIdentifier($account['identifier']),
                'index' => $index,
                'trailer' => $this->initAccountTrailer($account['trailer'])
            ]);

            $this->accounts[] = $accountResult;
            $this->cacheAccount($account['group'], $accountResult);
        }

        return $this;
    }

    /**
     * Instantiate errors.
     *
     * @param mixed[] $errors
     *
     * @return self
     */
    private function initErrors(array $errors): self
    {
        foreach ($errors as $error) {
            $this->addError($error['line'], $error['line_number']);
        }

        return $this;
    }

    /**
     * Instantiate file.
     *
     * @param mixed[] $file
     *
     * @return self
     */
    private function initFile(array $file): self
    {
        if (isset($file['header'], $file['trailer']) === false) {
            return $this;
        }

        $this->file = $this->instantiateNaiResult(File::class, [
            'header' => $this->initFileHeader($file['header']),
            'trailer' => $this->initFileTrailer($file['trailer'])
        ]);

        return $this;
    }

    /**
     * Instantiate file header.
     *
     * @param mixed[] $header
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Files\Header|null
     */
    private function initFileHeader(array $header): ?FilerHeader
    {
        return $this->instantiateSimpleItem([
            'code',
            'senderId',
            'receiverId',
            'fileCreationDate',
            'fileCreationTime',
            'fileSequenceNumber',
            'physicalRecordLength',
            'blockingFactor'
        ], FilerHeader::class, $header);
    }

    /**
     * Instantiate file trailer.
     *
     * @param mixed[] $trailer
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Files\Trailer|null
     */
    private function initFileTrailer(array $trailer): ?FileTrailer
    {
        return $this->instantiateSimpleItem([
            'code',
            'fileControlTotalA',
            'numberOfGroups',
            'numberOfRecords',
            'fileControlTotalB'
        ], FileTrailer::class, $trailer);
    }

    /**
     * Instantiate group header.
     *
     * @param mixed[] $header
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Groups\Header|null
     */
    private function initGroupHeader(array $header): ?GroupHeader
    {
        return $this->instantiateSimpleItem([
            'code',
            'ultimateReceiverId',
            'originatorReceiverId',
            'groupStatus',
            'asOfDate',
            'asOfTime'
        ], GroupHeader::class, $header);
    }

    /**
     * Instantiate group trailer.
     *
     * @param mixed[] $trailer
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Groups\Trailer|null
     */
    private function initGroupTrailer(array $trailer): ?GroupTrailer
    {
        return $this->instantiateSimpleItem([
            'code',
            'groupControlTotalA',
            'numberOfAccounts',
            'groupControlTotalB'
        ], GroupTrailer::class, $trailer);
    }

    /**
     * Instantiate groups.
     *
     * @param mixed[] $groups
     *
     * @return self
     */
    private function initGroups(array $groups): self
    {
        foreach ($groups as $index => $group) {
            if (isset($group['header'], $group['trailer']) === false) {
                continue;
            }

            $this->groups[] = $this->instantiateNaiResult(Group::class, [
                'header' => $this->initGroupHeader($group['header']),
                'index' => $index,
                'trailer' => $this->initGroupTrailer($group['trailer'])
            ]);
        }

        return $this;
    }

    /**
     * Instantiate transactions.
     *
     * @param mixed[] $transactions
     *
     * @return self
     */
    private function initTransactions(array $transactions): self
    {
        foreach ($transactions as $transaction) {
            $data = $this->getTransactionDataFromLine($transaction['line'], $transaction['line_number']);

            if ($data === null) {
                continue;
            }

            $transactionResult = $this->instantiateNaiResult(Transaction::class, \array_merge($data, [
                'account' => $transaction['account'] - 1, // Indexes coming from parser start from 1, we want 0
                'transactionDetails' => $this->getTransactionCodeDetails($data['transactionCode'])
            ]));

            $this->transactions[] = $transactionResult;
            $this->cacheTransaction($transaction['account'], $transactionResult);
        }

        return $this;
    }

    /**
     * Instantiate Nai result object and pass the context as parameter.
     *
     * @param string $resultClass
     * @param mixed[] $data
     *
     * @return mixed
     */
    private function instantiateNaiResult(string $resultClass, array $data)
    {
        return new $resultClass($this, $data);
    }

    /**
     * Instantiate simple item for given attributes, class and array.
     *
     * @param string[] $attributes
     * @param string $class
     * @param mixed[] $item
     *
     * @return mixed
     */
    private function instantiateSimpleItem(array $attributes, string $class, array $item)
    {
        $data = $this->getDataFromLine($attributes, $item['line'], $item['line_number']);

        if ($data === null) {
            return null;
        }

        return new $class($data);
    }
}
