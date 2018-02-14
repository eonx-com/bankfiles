<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Aba\Objects;

use EoneoPay\BankFiles\Generators\BaseObject;
use EoneoPay\BankFiles\Generators\Interfaces\GeneratorInterface;

class FileTotalRecord extends BaseObject
{
    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'fileUserNetTotalAmount' => GeneratorInterface::VALIDATION_RULE_NUMERIC,
            'fileUserCreditTotalAmount' => GeneratorInterface::VALIDATION_RULE_NUMERIC,
            'fileUserDebitTotalAmount' => GeneratorInterface::VALIDATION_RULE_NUMERIC,
            'fileUserCountOfRecordsType' => GeneratorInterface::VALIDATION_RULE_NUMERIC
        ];
    }

    /**
     * Get attributes padding configuration as [<attribute> => [<length>, <string>, <type>]].
     * @see http://php.net/manual/en/function.str-pad.php
     *
     * @return array
     */
    protected function getAttributesPaddingRules(): array
    {
        return [
            'blank1' => [12],
            'blank2' => [24],
            'blank3' => [40]
        ];
    }

    /**
     * Return object attributes.
     *
     * @return array
     */
    protected function initAttributes(): array
    {
        return [
            'recordType',
            'bsbFiller',
            'blank1',
            'fileUserNetTotalAmount',
            'fileUserCreditTotalAmount',
            'fileUserDebitTotalAmount',
            'blank2',
            'fileUserCountOfRecordsType',
            'blank3'
        ];
    }

    /**
     * Return record type.
     *
     * @return string
     */
    protected function initRecordType(): string
    {
        return '7';
    }
}
