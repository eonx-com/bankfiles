<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators;

use EoneoPay\BankFiles\Generators\Exceptions\ValidationNotAnObjectException;
use Tests\EoneoPay\BankFiles\Generators\Stubs\GeneratorStub;

class BaseGeneratorTest extends TestCase
{
    /**
     * Should throw exception if target is not an object
     *
     * @group Generators-Base
     */
    public function testShouldThrowExceptionIfTargetIsNotAnObject(): void
    {
        $this->expectException(ValidationNotAnObjectException::class);

        new GeneratorStub([]);
    }
}
