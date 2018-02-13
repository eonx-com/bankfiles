<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Brf\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;

class Header extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'billerCode',
        'billerShortName',
        'billerCreditBSB',
        'billerCreditAccount',
        'fileCreationDate',
        'fileCreationTime',
        'filler'
    ];
}
