<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Groups;

use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method string getAsOfDate()
 * @method string getAsOfTime()
 * @method string getCode()
 * @method string getGroupStatus()
 * @method string getOriginatorReceiverId()
 * @method string getUltimateReceiverId()
 */
class Header extends BaseResult
{
    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return [
            'asOfDate',
            'asOfTime',
            'code',
            'groupStatus',
            'originatorReceiverId',
            'ultimateReceiverId'
        ];
    }
}
