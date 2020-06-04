<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\DirectEntry\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\DirectEntry\HasAmounts;

/**
 * @method string|null getAccountName()
 * @method string|null getAccountNumber()
 * @method string|null getBsb()
 * @method string|null getIndicator()
 * @method string|null getLodgmentReference()
 * @method string|null getRecordType()
 * @method string|null getRemitterName()
 * @method string|null getTraceAccountNumber()
 * @method string|null getTraceBsb()
 * @method string|null getTxnCode()
 */
class Transaction extends BaseResult
{
    use HasAmounts;

    /**
     * Get amount on transaction as dollar
     */
    public function getAmount(): string
    {
        return $this->createAmount($this->data['amount'] ?? '');
    }

    /**
     * Get withholding tax on transaction as dollar
     */
    public function getWithholdingTax(): string
    {
        return $this->createAmount($this->data['withholdingTax'] ?? '');
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
            'amount',
            'bsb',
            'indicator',
            'lodgmentReference',
            'recordType',
            'remitterName',
            'traceAccountNumber',
            'traceBsb',
            'txnCode',
            'withholdingTax',
        ];
    }
}
