<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Stubs;

use EoneoPay\BankFiles\Generators\BaseGenerator;
use EoneoPay\BankFiles\Generators\Exceptions\LengthExceedsException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException;
use EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException;

class StubGenerator extends BaseGenerator
{
    private $descriptiveRecord;

    /**
     * StubGenerator constructor.
     *
     * @param $descriptiveRecord
     *
     * @throws LengthExceedsException
     * @throws ValidationFailedException
     * @throws ValidationNotAnObjectException
     */
    public function __construct($descriptiveRecord)
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
     *
     * @throws LengthExceedsException
     */
    protected function validateLineLengths(): void
    {
        //
    }
}
