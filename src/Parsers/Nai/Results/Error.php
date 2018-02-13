<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method getLine
 */
class Error extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'line'
    ];
}
