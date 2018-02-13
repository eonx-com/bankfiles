<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Aba\Objects;

use EoneoPay\BankFiles\Generators\BaseObject;

class DescriptiveRecord extends BaseObject
{
    /** @var array $attributes */
    protected $attributes = [
        'recordType',
        'blank1',
        'reelSequenceNumber',
        'userFinancialInstitution',
        'black2',
        'nameOfUseSupplyingFile',
        'numberOfUseSupplyingFile',
        'descriptionOfEntries',
        'dateToBeProcessed',
        'blank3'
    ];
}
