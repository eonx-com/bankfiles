<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Brf\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;

class Error extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'line'
    ];
}
