<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\Nai\ControlTotal;

/**
 * @method getNumberOfAccounts
 */
class GroupTrailer extends BaseResult
{
    use ControlTotal;

    /** @var array $attributes */
    protected $attributes = [
        'code',
        'groupControlTotalA',
        'numberOfAccounts',
        'groupControlTotalB'
    ];

    /**
     * Return group control total A
     *
     * @return float
     */
    public function getGroupControlTotalA()
    {
        return $this->formatAmount($this->data['groupControlTotalA']);
    }

    /**
     * Return group control total B
     *
     * @return float
     */
    public function getGroupControlTotalB()
    {
        return $this->formatAmount($this->data['groupControlTotalB']);
    }
}
