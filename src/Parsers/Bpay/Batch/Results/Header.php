<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Batch\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\Utils\DateTime;

/**
 * @method string|null getCustomerId()
 * @method string|null getCustomerShortName()
 * @method string|null getProcessingDate()
 * @method string|null getRestOfRecord()
 */
class Header extends BaseResult
{
    /**
     * Get processing date as an object
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException If datetime constructor string is invalid
     */
    public function getProcessingDateObject(): ?DateTime
    {
        $value = $this->data['processingDate'] ?? null;

        if ($value === null) {
            return null;
        }

        return new DateTime($value);
    }

    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return [
            'customerId',
            'customerShortName',
            'processingDate',
            'restOfRecord',
        ];
    }
}
