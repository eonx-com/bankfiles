<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use DateTime;
use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method getAsOfTime()
 * @method string getCode()
 * @method getGroupStatus()
 * @method getOriginatorReceiverId()
 * @method getUltimateReceiverId()
 */
class GroupHeader extends BaseResult
{
    /**
     * Convert to DateTime object and return
     *
     * @return DateTime
     */
    public function getAsOfDate(): ?DateTime
    {
        return $this->data['asOfDate'] ? new DateTime($this->data['asOfDate']) : null;
    }

    /**
     * Return object attributes.
     *
     * @return array
     */
    protected function initAttributes(): array
    {
        return [
            'code',
            'ultimateReceiverId',
            'originatorReceiverId',
            'groupStatus',
            'asOfDate',
            'asOfTime'
        ];
    }
}
