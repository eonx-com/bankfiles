<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method string getValue()
 * @method array getAttributes()
*/
class Issue extends BaseResult
{
    /**
     * Return object attributes.
     *
     * @return array
     */
    protected function initAttributes(): array
    {
        return [
            'value',
            'attributes'
        ];
    }
}
