<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Stubs;

use EoneoPay\BankFiles\Parsers\BaseResult;

class StubResult extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'biller'
    ];
}
