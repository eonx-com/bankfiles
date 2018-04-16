<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Accounts;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\Utils\Collection;
use EoneoPay\Utils\Interfaces\CollectionInterface;

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
     * @return \EoneoPay\Utils\Interfaces\CollectionInterface
     */
    public function getTransactionCodes(): CollectionInterface
    {
        return new Collection($this->data['transactionCodes']);
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
