<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Brf\Exceptions;

use Exception;

class InvalidSignFieldException extends Exception
{
    /** @var int $code */
    protected $code = 404;

    /** @var string $message */
    protected $message = 'Invalid signed field';
}
