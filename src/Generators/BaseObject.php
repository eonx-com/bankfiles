<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Generators;

use EoneoPay\BankFiles\AbstractDataBag;

class BaseObject extends AbstractDataBag
{
    /**
     * Return attribute values as single line
     *
     * @return string
     */
    public function getAttributesAsLine(): string
    {
        return \implode(array_values($this->data));
    }
    
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
     * Set the value of the attribute
     *
     * @param string $attribute
     * @param $value
     *
     * @return self
     */
    public function setAttribute(string $attribute, $value = ''): self
    {
        $this->data[$attribute] = $value;

        return $this;
    }
}
