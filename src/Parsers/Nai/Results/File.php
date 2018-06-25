<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Results\Files\Header;
use EoneoPay\BankFiles\Parsers\Nai\Results\Files\Trailer;

/**
 * @method Header getHeader()
 * @method Trailer getTrailer()
 */
class File extends AbstractNaiResult
{
    /**
     * Get file groups.
     *
     * @return \EoneoPay\BankFiles\Parsers\Nai\Results\Group[]
     */
    public function getGroups(): array
    {
        return $this->context->getGroups();
    }

    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return [
            'header',
            'trailer'
        ];
    }
}
