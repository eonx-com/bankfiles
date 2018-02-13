<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack\Results;

use DateTime;
use EoneoPay\BankFiles\Parsers\BaseResult;
use Illuminate\Support\Collection;

/**
 * Class PaymentAcknowledgement
 *
 * @method array|null getAttributes
 * @method array|null getPaymentId
 * @method array|null getOriginalMessageId
 * @method array|null getCustomerId
 * @method array|null getCompanyName
 * @method array|null getUserMessage
 * @method array|null getDetailedMessage
 * @method array|null getOriginalFilename
 * @method array|null getOriginalReference
 * @method Collection getIssues
 */
class PaymentAcknowledgement extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
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

    /**
     * Convert dateTime into DateTime object
     *
     * @return array|null
     */
    public function getDateTime(): ?array
    {
        if (isset($this->data['dateTime']['@value']) && !($this->data['dateTime']['@value'] instanceof DateTime)) {
            $this->data['dateTime']['@value'] = new DateTime($this->data['dateTime']['@value']);
        }

        return $this->data['dateTime'];
    }
}
