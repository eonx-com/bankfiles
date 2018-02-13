<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Bpay\Objects;

use EoneoPay\BankFiles\Generators\BaseObject;

class Header extends BaseObject
{
    /** @var array $attributes */
    protected $attributes = [
        'recordType',
        'batchCustomerId',
        'customerShortName',
        'processingDate',
        'restOfRecord',
    ];
}
