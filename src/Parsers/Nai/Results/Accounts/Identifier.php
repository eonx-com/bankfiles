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
 * @method mixed[] getTransactionCodes()
 */
class Identifier extends BaseResult
{
    /**
     * Return object attributes.
     *
     * @return string[]
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
