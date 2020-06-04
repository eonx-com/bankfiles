<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\Utils\Collection;
use EoneoPay\Utils\DateTime;

/**
 * @method mixed getAttributes()
 * @method mixed getPaymentId()
 * @method mixed getOriginalMessageId()
 * @method mixed getCustomerId()
 * @method mixed getCompanyName()
 * @method mixed getUserMessage()
 * @method mixed getDetailedMessage()
 * @method mixed getOriginalFilename()
 * @method mixed getOriginalReference()
 * @method Collection getIssues()
 */
class PaymentAcknowledgement extends BaseResult
{
    /**
     * Convert dateTime into DateTime object
     *
     * @return \DateTime[]|null
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException If datetime constructor string is invalid
     */
    public function getDateTime(): ?array
    {
        if (isset($this->data['dateTime']['@value']) &&
            ($this->data['dateTime']['@value'] instanceof DateTime) === false
        ) {
            $this->data['dateTime']['@value'] = new DateTime($this->data['dateTime']['@value']);
        }

        return $this->data['dateTime'];
    }

    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return [
            'attributes',
            'paymentId',
            'originalMessageId',
            'dateTime',
            'customerId',
            'companyName',
            'userMessage',
            'detailedMessage',
            'originalFilename',
            'originalReference',
            'issues',
        ];
    }
}
