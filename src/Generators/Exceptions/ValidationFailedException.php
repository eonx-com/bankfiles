<?php

namespace EoneoPay\BankFiles\Generators\Exceptions;

use Exception;
use Throwable;

class ValidationFailedException extends Exception
{
    /** @var array $errors */
    private $errors;

    /**
     * ValidationFailedException constructor.
     *
     * @param array $errors
     * @param null|string $message
     * @param int|null $code
     * @param null|Throwable $previous
     */
    public function __construct(array $errors, ?string $message = null, ?int $code = null, ?Throwable $previous = null)
    {
        $this->errors = $errors;
        $message = \sprintf('%s. %s', $message ?? '', $this->getErrorsToString());

        parent::__construct($message, $code ?? 0, $previous);
    }

    /**
     * Get validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get validation errors as string representation.
     *
     * @return string
     */
    public function getErrorsToString(): string
    {
        $pattern = '[attribute => %s, value => %s, rule => %s]';
        $errorsToString = '';

        foreach ($this->errors as $error) {
            $errorsToString .= \sprintf($pattern, $error['attribute'], $error['value'], $error['rule']);
        }

        return $errorsToString;
    }
}
