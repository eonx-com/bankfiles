<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Accounts;

use EoneoPay\BankFiles\Parsers\BaseResult;
use Illuminate\Support\Collection;

/**
 * @method string getCode()
 * @method string getCommercialAccountNumber()
 * @method string getCurrencyCode()
 */
class Identifier extends BaseResult
{
    /**
     * Return collection of transactions
     *
     * @return Collection
     */
    public function getTransactionCodes(): Collection
    {
        return \collect($this->data['transactionCodes']);
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
            'commercialAccountNumber',
            'currencyCode',
            'transactionCodes'
        ];
    }
}
