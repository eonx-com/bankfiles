<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Stubs;

use EoneoPay\BankFiles\Generators\BaseGenerator;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException;

class GeneratorStub extends BaseGenerator
{
    /**
     * @var array
     */
    private $descriptiveRecord;

    /**
     * StubGenerator constructor.
     *
     * @param array $descriptiveRecord
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException
     */
    public function __construct(array $descriptiveRecord)
    {
        $this->descriptiveRecord = $descriptiveRecord;

        $this->validateLineLengths();

        $this->generate();
    }

    /**
     * Generate
     *
     * @return void
     *
     * @throws ValidationFailedException
     * @throws ValidationNotAnObjectException
     */
    protected function generate(): void
    {
        /** @noinspection PhpParamsInspection Intentionally set to array to generate exception */
        $this->validateAttributes($this->descriptiveRecord, []);
    }

    /**
     * Return the defined line length of a generators
     *
     * @return int
     */
    protected function getLineLength(): int
    {
        return 120;
    }

    /**
     * Check if record length is no more than defined characters
     *
     * @return void
     */
    protected function validateLineLengths(): void
    {
        //
    }
}