<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Brf\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\Exceptions\InvalidSignFieldException;
use EoneoPay\BankFiles\Parsers\Bpay\Brf\SignedFieldsTrait;

class Trailer extends BaseResult
{
    use SignedFieldsTrait;

    /**
     * Get the amount of error correction and type
     *
     * @return mixed[]
     *
     * @throws \EoneoPay\BankFiles\Parsers\Bpay\Brf\Exceptions\InvalidSignFieldException
     */
    public function getAmountOfErrorCorrections(): array
    {
        return $this->getTrailerAmount('amountOfErrorCorrections');
    }

    /**
     * Get the amount of payment and type
     *
     * @return mixed[]
     *
     * @throws \EoneoPay\BankFiles\Parsers\Bpay\Brf\Exceptions\InvalidSignFieldException
     */
    public function getAmountOfPayments(): array
    {
        return $this->getTrailerAmount('amountOfPayments');
    }

    /**
     * Get the amount fo reversal and type
     *
     * @return mixed[]
     *
     * @throws \EoneoPay\BankFiles\Parsers\Bpay\Brf\Exceptions\InvalidSignFieldException
     */
    public function getAmountOfReversals(): array
    {
        return $this->getTrailerAmount('amountOfReversals');
    }

    /**
     * Get the settlement amount and type
     *
     * @return mixed[]
     *
     * @throws \EoneoPay\BankFiles\Parsers\Bpay\Brf\Exceptions\InvalidSignFieldException
     */
    public function getSettlementAmount(): array
    {
        return $this->getTrailerAmount('settlementAmount');
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
            'numberOfPayments',
            'amountOfPayments',
            'numberOfErrorCorrections',
            'amountOfErrorCorrections',
            'numberOfReversals',
            'amountOfReversals',
            'settlementAmount',
            'filler'
        ];
    }

    /**
     * Get the trailer amount and convert to proper value based on signed field
     *
     * @param string $attrAmount
     *
     * @return mixed[]
     *
     * @throws \EoneoPay\BankFiles\Parsers\Bpay\Brf\Exceptions\InvalidSignFieldException
     */
    private function getTrailerAmount(string $attrAmount): array
    {
        $sfCode = \substr($this->data[$attrAmount], 14);
        $sfValue = $this->getSignedFieldValue($sfCode);

        if ($sfValue === null) {
            throw new InvalidSignFieldException(\sprintf('Invalid signed amount: %s', $attrAmount));
        }

        $amountOfPayments = \substr($this->data[$attrAmount], 0, 14) . $sfValue['value'];

        $cents = \substr($amountOfPayments, 13, 2);
        $amount = \substr($this->data[$attrAmount], 0, 13);
        $amount = (int)$amount . '.' . $cents;

        return ['amount' => $amount, 'type' => $sfValue['type']];
    }
}
