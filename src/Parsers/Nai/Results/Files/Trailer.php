<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Files;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\Nai\ControlTotal;

/**
 * @method string getCode()
 * @method string getNumberOfGroups()
 * @method string getNumberOfRecords()
 */
class Trailer extends BaseResult
{
    use ControlTotal;

    /**
     * Return file control total A
     *
     * @return float
     */
    public function getFileControlTotalA(): float
    {
        return $this->formatAmount($this->data['fileControlTotalA']);
    }

    /**
     * Return file control total B
     *
     * @return float
     */
    public function getFileControlTotalB(): float
    {
        return $this->formatAmount($this->data['fileControlTotalB']);
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
            'fileControlTotalA',
            'fileControlTotalB',
            'numberOfGroups',
            'numberOfRecords'
        ];
    }
}
