<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Accounts;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\Nai\Results\Account;

/**
 * @method Account getAccount()
 * @method string getAmount()
 * @method string getCode()
 * @method string getFundsType()
 * @method string getReferenceNumber()
 * @method string getText()
 * @method string getTransactionCode()
 * @method array getTransactionDetails()
 */
class Transaction extends BaseResult
{
    /**
     * Set Account.
     *
     * @param Account $account
     *
     * @return self
     */
    public function setAccount(Account $account): self
    {
        $this->data['account'] = $account;

        return $this;
    }

    /**
     * Return object attributes.
     *
     * @return array
     */
    protected function initAttributes(): array
    {
        return [
            'code',
            'transactionCode',
            'transactionDetails',
            'amount',
            'fundsType',
            'referenceNumber',
            'text',
            'account'
        ];
    }
}
