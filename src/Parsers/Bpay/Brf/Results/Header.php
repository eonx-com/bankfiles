<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Brf\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;

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
            'billerCode',
            'billerShortName',
            'billerCreditBSB',
            'billerCreditAccount',
            'fileCreationDate',
            'fileCreationTime',
            'filler'
        ];
    }
}
