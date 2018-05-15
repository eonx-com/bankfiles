<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Accounts;

use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method string getAccountControlTotalA()
 * @method string getAccountControlTotalB()
 * @method string getCode()
 */
class Trailer extends BaseResult
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
            'accountControlTotalA',
            'accountControlTotalB'
        ];
    }
}
