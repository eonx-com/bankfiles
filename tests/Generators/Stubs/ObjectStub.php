<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Stubs;

use EoneoPay\BankFiles\Generators\BaseObject;

class ObjectStub extends BaseObject
{
    /**
     * Get validation rules.
     *
     * @return mixed[]
     */
    public function getValidationRules(): array
    {
        return [];
    }

    /**
     * Get attributes padding configuration as [<attribute> => [<length>, <string>, <type>]].
     *
     * @see http://php.net/manual/en/function.str-pad.php
     *
     * @return mixed[]
     */
    protected function getAttributesPaddingRules(): array
    {
        return [];
    }

    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return [
            'accountName',
            'accountNumber',
        ];
    }

    /**
     * Return record type.
     */
    protected function initRecordType(): string
    {
        return '1';
    }
}
