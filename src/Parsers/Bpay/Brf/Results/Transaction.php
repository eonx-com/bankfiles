<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Brf\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\Utils\DateTime;

/**
 * @method getBillerCode()
 * @method getCustomerReferenceNumber()
 * @method getPaymentInstructionType()
 * @method getTransactionReferenceNumber()
 * @method getOriginalReferenceNumber()
 * @method getErrorCorrectionReason()
 * @method getPaymentTime()
 * @method getFiller()
 */
class Transaction extends BaseResult
{
    /**
     * Convert amount into float and return
     */
    public function getAmount(): float
    {
        return (float)\sprintf(
            '%d.%d',
            (int)\substr($this->data['amount'], 0, 10),
            (int)\substr($this->data['amount'], 10, 2)
        );
    }

    /**
     * Convert to DateTime object and return
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException If datetime constructor string is invalid
     */
    public function getPaymentDate(): DateTime
    {
        return new DateTime($this->data['paymentDate']);
    }

    /**
     * Convert to DateTime object and return
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException If datetime constructor string is invalid
     */
    public function getSettlementDate(): DateTime
    {
        return new DateTime($this->data['settlementDate']);
    }

    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return [
            'billerCode',
            'customerReferenceNumber',
            'paymentInstructionType',
            'transactionReferenceNumber',
            'originalReferenceNumber',
            'errorCorrectionReason',
            'amount',
            'paymentDate',
            'paymentTime',
            'settlementDate',
            'filler',
        ];
    }
}
