<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Transactions;

use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method string getDescription()
 * @method string getParticulars()
 * @method string getType()
 */
class Details extends BaseResult
{
    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return [
            'description',
            'particulars',
            'type',
        ];
    }
}
