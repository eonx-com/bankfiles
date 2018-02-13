<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers;

abstract class BaseParser
{
    /** @var string $contents */
    protected $contents;

    /**
     * BaseParser constructor.
     *
     * @param string $contents
     */
    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }

    /**
     * Process parsing
     *
     * @return void
     */
    abstract protected function process(): void;
}
