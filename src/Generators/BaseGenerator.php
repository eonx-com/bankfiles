<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators;

use EoneoPay\BankFiles\Generators\Exceptions\LengthExceedsException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException;
use EoneoPay\BankFiles\Generators\Interfaces\GeneratorInterface;

abstract class BaseGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    protected $breakLine = self::BREAK_LINE_UNIX;

    /**
     * @var string
     */
    protected $contents = '';

    /**
     * @var array $validationRules
     */
    protected static $validationRules = [
        self::VALIDATION_RULE_ALPHA => '/[^A-Za-z0-9 &\',-\.\/\+\$\!%\(\)\*\#=:\?\[\]_\^@]/',
        self::VALIDATION_RULE_NUMERIC => '/[^0-9-]/',
        self::VALIDATION_RULE_BSB => '/^\d{3}(\-)\d{3}/'
    ];

    /**
     * Return contents
     *
     * @return string
     */
    public function getContents(): string
    {
        $this->generate();

        return $this->contents;
    }

    /**
     * Set break lines.
     *
     * @param string $breakLine
     *
     * @return \EoneoPay\BankFiles\Generators\BaseGenerator
     */
    public function setBreakLines(string $breakLine): self
    {
        $this->breakLine = $breakLine;

        return $this;
    }

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
     * Validate object attributes
     *
     * @param $object
     * @param null|array $rules
     *
     * @return void
     *
     * @throws ValidationFailedException
     * @throws ValidationNotAnObjectException
     */
    protected function validateAttributes($object, ?array $rules = null): void
    {
        if (!\is_object($object)) {
            throw new ValidationNotAnObjectException('Not an object exception');
        }

        $errors = [];

        foreach ($rules ?? [] as $attribute => $rule) {
            $value = (string) $object->{'get' . \ucfirst($attribute)}();

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
                        $errors[] = \compact('attribute', 'value', 'rule');
                    }
                    break;
            }
        }

        if (!empty($errors)) {
            throw new ValidationFailedException($errors, 'Validation Errors');
        }
    }

    /**
     * Add line to contents.
     *
     * @param string $line
     *
     * @return void
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\LengthExceedsException
     */
    protected function writeLine(string $line): void
    {
        $this->checkLineLength($line);
        $this->contents .= $line . $this->breakLine;
    }

    /**
     * Add lines for given objects.
     *
     * @param array $objects
     *
     * @return void
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\LengthExceedsException
     */
    protected function writeLinesForObjects(array $objects): void
    {
        foreach ($objects as $object) {
            /** @var \EoneoPay\BankFiles\Generators\BaseObject */
            $this->validateAttributes($object, $object->getValidationRules());
            $this->writeLine($object->getAttributesAsLine());
        }
    }
}
