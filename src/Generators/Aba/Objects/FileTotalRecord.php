<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Aba\Objects;

use EoneoPay\BankFiles\Generators\BaseObject;

class FileTotalRecord extends BaseObject
{
    /** @var array $attributes */
    protected $attributes = [
        'recordType',
        'bsbFiller',
        'blank1',
        'fileUserNetTotalAmount',
        'fileUserCreditTotalAmount',
        'fileUserDebitTotalAmount',
        'blank2',
        'fileUserCountOfRecordsType',
        'blank3',
    ];
}
