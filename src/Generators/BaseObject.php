<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators;

use EoneoPay\BankFiles\AbstractDataBag;

abstract class BaseObject extends AbstractDataBag
{
    /**
     * BaseResult constructor.
     *
     * @param array|null $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct(\array_merge(['recordType' => $this->initRecordType()], $data ?? []));
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    abstract public function getValidationRules(): array;

    /**
     * Return all the attributes
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->data;
    }

    /**
     * Return attribute values as single line
     *
     * @return string
     */
    public function getAttributesAsLine(): string
    {
        $line = [];
        $paddingRules = $this->getAttributesPaddingRules();

        foreach ($this->attributes as $attribute) {
            $value = $this->data[$attribute] ?? '';

            if (isset($paddingRules[$attribute])) {
                \array_unshift($paddingRules[$attribute], $value);

                // Ensure first attribute is a string
                if (!\is_string($paddingRules[$attribute][0])) {
                    $paddingRules[$attribute][0] = (string)$paddingRules[$attribute][0];
                }

                $value = \str_pad(...$paddingRules[$attribute]);
            }

            $line[] = $value;
        }

        return \implode($line);
    }

    /**
     * Set the value of the attribute
     *
     * @param string $attribute
     * @param null|string|int $value
     *
     * @return self
     */
    public function setAttribute(string $attribute, $value = null): self
    {
        $this->data[$attribute] = $value ?? '';

        return $this;
    }

    /**
     * Get attributes padding configuration as [<attribute> => [<length>, <string>, <type>]].
     * @see http://php.net/manual/en/function.str-pad.php
     *
     * @return array
     */
    abstract protected function getAttributesPaddingRules(): array;

    /**
     * Return record type.
     *
     * @return string
     */
    abstract protected function initRecordType(): string;
}
