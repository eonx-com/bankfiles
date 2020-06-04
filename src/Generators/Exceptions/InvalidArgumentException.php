<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Exceptions;

use EoneoPay\Utils\Exceptions\RuntimeException;

class InvalidArgumentException extends RuntimeException
{
    /**
     * Get Error code.
     */
    public function getErrorCode(): int
    {
        return self::DEFAULT_ERROR_CODE_RUNTIME;
    }

    /**
     * Get Error sub-code.
     */
    public function getErrorSubCode(): int
    {
        return self::DEFAULT_ERROR_SUB_CODE;
    }
}
