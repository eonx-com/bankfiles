<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Bpay\Objects;

use EoneoPay\BankFiles\Generators\BaseObject;

class Transaction extends BaseObject
{
    /** @var array $attributes */
    protected $attributes = [
        'recordType',
        'billerCode',
        'paymentAccountBSB',
        'paymentAccountNumber',
        'customerReferenceNumber',
        'amount',
        'lodgementReference1',
        'lodgementReference2',
        'lodgementReference3',
        'restOfRecord'
    ];
}
