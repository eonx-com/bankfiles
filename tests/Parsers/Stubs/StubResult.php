<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Stubs;

use EoneoPay\BankFiles\Parsers\BaseResult;

class StubResult extends BaseResult
{
    /**
     * Return object attributes.
     *
     * @return array
     */
    protected function initAttributes(): array
    {
        return ['biller'];
    }
}
