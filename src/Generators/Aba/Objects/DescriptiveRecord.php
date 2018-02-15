<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Aba\Objects;

use EoneoPay\BankFiles\Generators\BaseObject;
use EoneoPay\BankFiles\Generators\Interfaces\GeneratorInterface;

class DescriptiveRecord extends BaseObject
{
    /**
     * BaseResult constructor.
     *
     * @param array|null $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct(\array_merge(['reelSequenceNumber' => '01'], $data ?? []));
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'nameOfUseSupplyingFile' => GeneratorInterface::VALIDATION_RULE_ALPHA,
            'numberOfUserSupplyingFile' => GeneratorInterface::VALIDATION_RULE_NUMERIC,
            'descriptionOfEntries' => GeneratorInterface::VALIDATION_RULE_ALPHA,
            'dateToBeProcessed' => GeneratorInterface::VALIDATION_RULE_DATE
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
            'blank1' => [17],
            'blank2' => [7],
            'blank3' => [40],
            'nameOfUseSupplyingFile' => [26],
            'numberOfUserSupplyingFile' => [6, '0', STR_PAD_LEFT],
            'descriptionOfEntries' => [12]
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
            'blank1',
            'reelSequenceNumber',
            'userFinancialInstitution',
            'black2',
            'nameOfUseSupplyingFile',
            'numberOfUserSupplyingFile',
            'descriptionOfEntries',
            'dateToBeProcessed',
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
        return '0';
    }
}
