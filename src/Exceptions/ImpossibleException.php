<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Exceptions;

use EoneoPay\Utils\Exceptions\RuntimeException;

/**
 * An exception for a situation that should never happen (null checks when it is known
 * that it will never be null, etc).
 */
final class ImpossibleException extends RuntimeException
{
    public function getErrorCode(): int
    {
        return self::DEFAULT_ERROR_CODE_RUNTIME + 899;
    }

    public function getErrorSubCode(): int
    {
        return 1;
    }
}
