<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Bpay\Objects;

use EoneoPay\BankFiles\Generators\BaseObject;
use EoneoPay\BankFiles\Generators\Interfaces\GeneratorInterface;

class Header extends BaseObject
{
    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'customerShortName' => GeneratorInterface::VALIDATION_RULE_ALPHA,
            'processingDate' => GeneratorInterface::VALIDATION_RULE_DATE
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
            'batchCustomerId' => [16],
            'customerShortName' => [20],
            'restOfRecord' => [99]
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
            'batchCustomerId',
            'customerShortName',
            'processingDate',
            'restOfRecord'
        ];
    }

    /**
     * Return record type.
     *
     * @return string
     */
    protected function initRecordType(): string
    {
        return '1';
    }
}
