<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Accounts;

use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method getAccountControlTotalA
 * @method getAccountControlTotalB
*/
class Trailer extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'code',
        'accountControlTotalA',
        'accountControlTotalB'
    ];
}
