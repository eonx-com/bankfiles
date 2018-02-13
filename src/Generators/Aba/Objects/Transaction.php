<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Aba\Objects;

use EoneoPay\BankFiles\Generators\BaseObject;

class Transaction extends BaseObject
{
    /** @var array $attributes */
    protected $attributes = [
        'recordType',
        'bsbNumber',
        'accountNumberToBeCreditedDebited',
        'indicator',
        'transactionCode',
        'amount',
        'titleOfAccountToBeCreditedDebited',
        'lodgementReference',
        'traceRecord',
        'accountNumber',
        'nameOfRemitter',
        'amountOfWithholdingTax'
    ];
}
