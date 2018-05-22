<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Identifier;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Trailer;
use EoneoPay\Utils\Collection;
use EoneoPay\Utils\Interfaces\CollectionInterface;

/**
 * @method Identifier getIdentifier()
 * @method Trailer getTrailer()
 */
class Account extends BaseResult
{
    /**
     * Return collection of transactions
     *
     * @return \EoneoPay\Utils\Interfaces\CollectionInterface
     */
    public function getTransactions(): CollectionInterface
    {
        return new Collection($this->data['transactions']);
    }

    /**
     * Return object attributes.
     *
     * @return string[]
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
