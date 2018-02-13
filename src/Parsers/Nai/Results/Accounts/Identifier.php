<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Accounts;

use EoneoPay\BankFiles\Parsers\BaseResult;
use Illuminate\Support\Collection;

/**
 * @method getCommercialAccountNumber
 * @method getCurrencyCode
 */
class Identifier extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'code',
        'commercialAccountNumber',
        'currencyCode',
        'transactionCodes'
    ];

    /**
     * Return collection of transactions
     *
     * @return Collection
     */
    public function getTransactionCodes(): Collection
    {
        return \collect($this->data['transactionCodes']);
    }
}
