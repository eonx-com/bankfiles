<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Accounts;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\Nai\ControlTotal;

/**
 * @method string getCode()
 */
class Trailer extends BaseResult
{
    use ControlTotal;

    /**
     * Get account control total A.
     */
    public function getAccountControlTotalA(): float
    {
        return $this->formatAmount($this->data['accountControlTotalA']);
    }

    /**
     * Get account control total B.
     */
    public function getAccountControlTotalB(): float
    {
        return $this->formatAmount($this->data['accountControlTotalB']);
    }

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
            'accountControlTotalB',
        ];
    }
}
