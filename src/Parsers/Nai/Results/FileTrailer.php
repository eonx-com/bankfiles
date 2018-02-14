<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\Nai\ControlTotal;

/**
 * @method string getCode()
 * @method getNumberOfGroups()
 * @method getNumberOfRecords()
 */
class FileTrailer extends BaseResult
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
     * @return array
     */
    protected function initAttributes(): array
    {
        return [
            'code',
            'fileControlTotalA',
            'numberOfGroups',
            'numberOfRecords',
            'fileControlTotalB'
        ];
    }
}
