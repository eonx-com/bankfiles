<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Identifier;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Trailer;
use Illuminate\Support\Collection;

/**
 * @method Identifier getIdentifier()
 * @method Trailer getTrailer()
 */
class Account extends BaseResult
{
    /**
     * Return collection of transactions
     *
     * @return Collection
     */
    public function getTransactions(): Collection
    {
        return \collect($this->data['transactions']);
    }

    /**
     * Return object attributes.
     *
     * @return array
     */
    protected function initAttributes(): array
    {
        return [
            'identifier',
            'transactions',
            'trailer'
        ];
    }
}
