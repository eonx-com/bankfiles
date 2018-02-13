<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Stubs;

use EoneoPay\BankFiles\Generators\BaseObject;

class StubObject extends BaseObject
{
    protected $attributes = [
        'accountName',
        'accountNumber'
    ];
}
