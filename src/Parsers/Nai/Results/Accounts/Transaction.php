<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Accounts;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\Nai\Results\Account;

class Transaction extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'code',
        'transactionCode',
        'transactionDetails',
        'amount',
        'fundsType',
        'referenceNumber',
        'text',
        'account'
    ];

    /**
     * Set Account
     *
     * @param $account
     *
     * @return self
     */
    public function setAccount(Account $account): self
    {
        $this->data['account'] = $account;

        return $this;
    }
}
