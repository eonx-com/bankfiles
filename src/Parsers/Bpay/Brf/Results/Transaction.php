<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Brf\Results;

use DateTime;
use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method getBillerCode
 * @method getReferenceNumber
 * @method getPaymentInstructionType
 * @method getTransactionReferenceNumber
 * @method getOriginalReferenceNumber
 * @method getErrorCorrectionReason
 * @method getPaymentTime
 * @method getFiller
 */
class Transaction extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
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

    /**
     * Convert amount into float and return
     *
     * @return float
     */
    public function getAmount(): float
    {
        return (float) \sprintf(
            '%d.%d',
            (int) \substr($this->data['amount'], 0, 10),
            (int) \substr($this->data['amount'], 10, 2)
        );
    }

    /**
     * Convert to DateTime object and return
     *
     * @return DateTime
     */
    public function getPaymentDate(): DateTime
    {
        return new DateTime($this->data['paymentDate']);
    }

    /**
     * Convert to DateTime object and return
     *
     * @return DateTime
     */
    public function getSettlementDate(): DateTime
    {
        return new DateTime($this->data['settlementDate']);
    }
}
