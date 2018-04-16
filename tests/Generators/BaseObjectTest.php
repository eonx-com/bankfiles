<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators;

use Tests\EoneoPay\BankFiles\Generators\Stubs\ObjectStub;

class BaseObjectTest extends TestCase
{
    /**
     * Should return all attributes
     *
     * @group Generator-BaseObject
     *
     * @return void
     */
    public function testShouldReturnAttributes(): void
    {
        $data = [
            'accountName' => 'John Doe',
            'accountNumber' => '11-222-33'
        ];

        $object = new ObjectStub($data);

        /** @noinspection UnnecessaryAssertionInspection Assertion neecessary for exact instance type */
        self::assertInternalType('array', $object->getAttributes());
        self::assertEquals($data, $object->getAttributes());
    }
}
