<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * Class Issue
 *
 * @method string getValue
 * @method array getAttribute
*/
class Issue extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'value',
        'attributes'
    ];
}
