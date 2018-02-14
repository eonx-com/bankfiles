<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators\Interfaces;

interface GeneratorInterface
{
    public const VALIDATION_RULE_ALPHA = 'alpha';
    public const VALIDATION_RULE_BSB = 'bsb';
    public const VALIDATION_RULE_DATE = 'date';
    public const VALIDATION_RULE_NUMERIC = 'numeric';

    /**
     * Return contents.
     *
     * @return string
     */
    public function getContents(): string;
}
