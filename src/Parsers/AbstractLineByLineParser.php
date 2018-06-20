<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers;

abstract class AbstractLineByLineParser extends BaseParser
{
    /**
     * AbstractLineByLineParser constructor.
     *
     * @param string $contents
     */
    public function __construct(string $contents)
    {
        parent::__construct($contents);

        $this->process();
    }

    /**
     * Process line and parse data
     *
     * @param int $lineNumber
     * @param string $line
     *
     * @return void
     */
    abstract protected function processLine(int $lineNumber, string $line): void;

    /**
     * Process parsing
     *
     * @return void
     */
    protected function process(): void
    {
        $contents = \explode(\PHP_EOL, $this->contents);
        $lineNumber = 1;

        foreach ($contents as $line) {
            $line = \trim($line);

            if ($line === '') {
                continue;
            }

            $this->processLine($lineNumber, $line);
            $lineNumber++;
        }
    }
}
