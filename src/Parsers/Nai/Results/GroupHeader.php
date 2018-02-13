<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use DateTime;
use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method getUltimateReceiverId
 * @method getOriginatorReceiverId
 * @method getGroupStatus
 * @method getAsOfTime
*/
class GroupHeader extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'code',
        'ultimateReceiverId',
        'originatorReceiverId',
        'groupStatus',
        'asOfDate',
        'asOfTime',
    ];

    /**
     * Convert to DateTime object and return
     *
     * @return DateTime
     */
    public function getAsOfDate(): ?DateTime
    {
        return $this->data['asOfDate'] ? new DateTime($this->data['asOfDate']) : null;
    }
}
