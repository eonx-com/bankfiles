<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Bpay\Objects;

use EoneoPay\BankFiles\Generators\BaseObject;
use EoneoPay\BankFiles\Generators\Interfaces\GeneratorInterface;

/**
 * @method string getAmount()
 * @method string getBillerCode()
 * @method string getPaymentAccountBsb()
 * @method string getPaymentAccountNumber()
 * @method string getCustomerReferenceNumber()
 */
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
            'billerCode' => GeneratorInterface::VALIDATION_RULE_NUMERIC,
            'paymentAccountBSB' => GeneratorInterface::VALIDATION_RULE_NUMERIC,
            'paymentAccountNumber' => GeneratorInterface::VALIDATION_RULE_NUMERIC,
            'customerReferenceNumber' => GeneratorInterface::VALIDATION_RULE_ALPHA,
            'amount' => GeneratorInterface::VALIDATION_RULE_NUMERIC
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
            'billerCode' => [10, '0', STR_PAD_LEFT],
            'customerReferenceNumber' => [20],
            'amount' => [13, '0', STR_PAD_LEFT],
            'lodgementReference1' => [10],
            'lodgementReference2' => [20],
            'lodgementReference3' => [50],
            'restOfRecord' => [5]
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
            'billerCode',
            'paymentAccountBSB',
            'paymentAccountNumber',
            'customerReferenceNumber',
            'amount',
            'lodgementReference1',
            'lodgementReference2',
            'lodgementReference3',
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
        return '2';
    }
}
