<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Stubs;

use EoneoPay\BankFiles\Generators\BaseGenerator;

class GeneratorStub extends BaseGenerator
{
    /**
     * @var mixed[]
     */
    private $descriptiveRecord;

    /**
     * @var mixed[]
     */
    private $transactions;

    /**
     * StubGenerator constructor.
     *
     * @param mixed[] $descriptiveRecord
     * @param mixed[] $transactions
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\LengthMismatchesException
     */
    public function __construct(array $descriptiveRecord, ?array $transactions = null)
    {
        $this->descriptiveRecord = $descriptiveRecord;
        $this->transactions = $transactions ?? [];

        $this->generate();
        $this->validateLineLengths();
    }

    /**
     * Generate
     *
     * @return void
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\LengthMismatchesException
     */
    protected function generate(): void
    {
        $this->writeLinesForObjects($this->transactions);
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
    }
}
