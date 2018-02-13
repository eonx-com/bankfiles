<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;
use Illuminate\Support\Collection;

class Account extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'identifier',
        'transactions',
        'trailer'
    ];

    /**
     * Return collection of transactions
     *
     * @return Collection
     */
    public function getTransactions(): Collection
    {
        return \collect($this->data['transactions']);
    }
}
