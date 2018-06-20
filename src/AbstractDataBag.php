<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles;

use EoneoPay\Utils\Str;

abstract class AbstractDataBag
{
    /**
     * @var mixed[]
     */
    protected $attributes;

    /**
     * @var mixed[]
     */
    protected $data = [];

    /**
     * BaseResult constructor.
     *
     * @param mixed[]|null $data
     */
    public function __construct(?array $data = null)
    {
        $this->attributes = $this->initAttributes() ?? [];

        foreach ($data ?? [] as $key => $value) {
            if (\in_array($key, $this->attributes, true)) {
                $this->data[$key] = $value;
            }
        }
    }

    /**
     * Return attribute's value
     *
     * @param string $method
     * @param mixed[] $parameters
     *
     * @return mixed|null
     */
    public function __call(string $method, array $parameters)
    {
        $type = \strtolower(\substr($method, 0, 3));
        $attribute = (new Str())->camel(\substr($method, 3));

        if ($type === 'get' && isset($this->data[$attribute])) {
            return $this->data[$attribute];
        }

        return null;
    }

    /**
     * Return object attributes.
     *
     * @return mixed[]
     */
    abstract protected function initAttributes(): array;
}
