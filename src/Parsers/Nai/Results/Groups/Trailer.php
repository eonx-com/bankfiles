<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Groups;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\Nai\ControlTotal;

/**
 * @method string getCode()
 * @method string getNumberOfAccounts()
 */
class Trailer extends BaseResult
{
    use ControlTotal;

    /**
     * Return group control total A
     *
     * @return float
     */
    public function getGroupControlTotalA(): float
    {
        return $this->formatAmount($this->data['groupControlTotalA']);
    }

    /**
     * Return group control total B
     *
     * @return float
     */
    public function getGroupControlTotalB(): float
    {
        return $this->formatAmount($this->data['groupControlTotalB']);
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
            'groupControlTotalA',
            'groupControlTotalB',
            'numberOfAccounts'
        ];
    }
}
