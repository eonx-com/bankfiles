<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators;

use EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException;
use Tests\EoneoPay\BankFiles\Generators\Stubs\GeneratorStub;

class BaseGeneratorTest extends TestCase
{
    /**
     * Should throw exception if target is not an object
     *
     * @return void
     *
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\ValidationFailedException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\InvalidArgumentException
     * @throws \EoneoPay\BankFiles\Generators\Exceptions\LengthMismatchesException
     */
    public function testShouldThrowExceptionIfTargetIsNotAnObject(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new GeneratorStub([], ['for-coverage']);
    }
}
