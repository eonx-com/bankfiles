<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators;

use EoneoPay\BankFiles\Generators\Exceptions\LengthExceedsException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException;

abstract class BaseGenerator
{
    const VALIDATION_RULE_ALPHA = 'alpha';
    const VALIDATION_RULE_BSB = 'bsb';
    const VALIDATION_RULE_DATE = 'date';
    const VALIDATION_RULE_NUMERIC = 'numeric';

    /**@var array $validationRules */
    protected static $validationRules = [
        self::VALIDATION_RULE_ALPHA => '/[^A-Za-z0-9 &\',-\.\/\+\$\!%\(\)\*\#=:\?\[\]_\^@]/',
        self::VALIDATION_RULE_NUMERIC => '/[^0-9-]/',
        self::VALIDATION_RULE_BSB => '/^\d{3}(\-)\d{3}/'
    ];

    /**
     * @var string
     */
    protected $contents = '';

    /**
     * Return contents
     *
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * Generate
     *
     * @return void
     */
    abstract protected function generate(): void;

    /**
     * Return the defined line length of a generator
     *
     * @return int
     */
    abstract protected function getLineLength(): int;

    /**
     * Check if record length is no more than defined characters
     *
     * @return void
     *
     * @throws LengthExceedsException
     */
    abstract protected function validateLineLengths(): void;

    /**
     * Check if line's length is greater than defined length
     *
     * @param string $line
     *
     * @return void
     *
     * @throws LengthExceedsException
     */
    protected function checkLineLength(string $line): void
    {
        if (\strlen($line) > $this->getLineLength()) {
            throw new LengthExceedsException(
                \sprintf('Length exceeds the defined %s maximum characters', $this->getLineLength())
            );
        }
    }

    /**
     * Validate object attributes
     *
     * @param $object
     * @param array $rules
     *
     * @return void
     *
     * @throws ValidationFailedException
     * @throws ValidationNotAnObjectException
     */
    protected function validateAttributes($object, array $rules = []): void
    {
        if (!\is_object($object)) {
            throw new ValidationNotAnObjectException('Not an object exception');
        }

        $errors = [];

        foreach ($rules as $attribute => $rule) {
            $value = (string)$object->{'get' . \ucfirst($attribute)}();

            switch ($rule) {
                case self::VALIDATION_RULE_BSB:
                    // 123-456 length must be 7 characters with '-' in the 4th position
                    if (!\preg_match(self::$validationRules[$rule], $value)) {
                        $errors[] = compact('attribute', 'value', 'rule');
                    }
                    break;

                case self::VALIDATION_RULE_DATE:
                    if (!\DateTime::createFromFormat('dmy', $value) && !\DateTime::createFromFormat('Ymd', $value)) {
                        $errors[] = compact('attribute', 'value', 'rule');
                    }
                    break;

                case self::VALIDATION_RULE_ALPHA:
                case self::VALIDATION_RULE_NUMERIC:
                    if (\preg_match(self::$validationRules[$rule], $value)) {
                        $errors[] = compact('attribute', 'value', 'rule');
                    }
                    break;
            }
        }

        if (!empty($errors)) {
            throw new ValidationFailedException($errors, 'Validation Errors');
        }
    }
}
