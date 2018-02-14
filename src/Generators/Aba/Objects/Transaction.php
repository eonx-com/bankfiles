<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Aba\Objects;

use EoneoPay\BankFiles\Generators\BaseObject;
use EoneoPay\BankFiles\Generators\Interfaces\GeneratorInterface;

class Transaction extends BaseObject
{
    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'bsbNumber' => GeneratorInterface::VALIDATION_RULE_BSB,
            'accountNumberToBeCreditedDebited' => GeneratorInterface::VALIDATION_RULE_ALPHA,
            'amount' => GeneratorInterface::VALIDATION_RULE_NUMERIC,
            'titleOfAccountToBeCreditedDebited' => GeneratorInterface::VALIDATION_RULE_ALPHA,
            'lodgementReference' => GeneratorInterface::VALIDATION_RULE_ALPHA,
            'traceRecord' => GeneratorInterface::VALIDATION_RULE_BSB,
            'accountNumber' => GeneratorInterface::VALIDATION_RULE_ALPHA,
            'nameOfRemitter' => GeneratorInterface::VALIDATION_RULE_ALPHA,
            'amountOfWithholdingTax' => GeneratorInterface::VALIDATION_RULE_NUMERIC
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
        return [];
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
            'bsbNumber',
            'accountNumberToBeCreditedDebited',
            'indicator',
            'transactionCode',
            'amount',
            'titleOfAccountToBeCreditedDebited',
            'lodgementReference',
            'traceRecord',
            'accountNumber',
            'nameOfRemitter',
            'amountOfWithholdingTax'
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
