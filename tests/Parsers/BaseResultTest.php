<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers;

use Tests\EoneoPay\BankFiles\Parsers\Stubs\ResultStub;

class BaseResultTest extends TestCase
{
    /**
     * Should return company name as biller
     *
     * @group Base-Result
     */
    public function testShouldReturnBiller(): void
    {
        $data = [
            'biller' => 'Company Name',
        ];

        $result = new ResultStub($data);

        self::assertSame($data['biller'], $result->getBiller());
    }

    /**
     * Should return null if attribute does not exist
     *
     * @group Base-Result
     */
    public function testShouldReturnNull(): void
    {
        $data = [
            'biller' => 'Company Name',
        ];

        $result = new ResultStub($data);

        self::assertNull($result->getWhatAttribute());
    }
}
