<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method string getLine()
 */
class Error extends BaseResult
{
    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return ['line'];
    }
}
