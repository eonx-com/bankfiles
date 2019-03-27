<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Identifier;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Trailer;

/**
 * @method Identifier getIdentifier()
 * @method Trailer getTrailer()
 */
class Account extends AbstractNaiResult
{
    /**
     * Get group.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Group|null
     */
    public function getGroup(): ?Group
    {
        return $this->context->getGroup($this->data['group']);
    }

    /**
     * Get transactions.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->context->getTransactionsForAccount($this->data['index']);
    }

    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return [
            'group',
            'identifier',
            'index',
            'trailer'
        ];
    }
}
