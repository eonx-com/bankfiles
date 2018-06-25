<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Results\Group;
use EoneoPay\BankFiles\Parsers\Nai\Results\Groups\Header;
use EoneoPay\BankFiles\Parsers\Nai\Results\Groups\Trailer;
use EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext;
use Mockery\MockInterface;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\Group
 */
class GroupTest extends TestCase
{
    /**
     * Result should return data as expected.
     *
     * @return void
     */
    public function testGetDataAsExpected(): void
    {
        $data = [
            'header' => new Header(),
            'index' => 2,
            'trailer' => new Trailer()
        ];

        $setExpectations = function (MockInterface $context) use ($data): void {
            $context
                ->shouldReceive('getFile')
                ->once()
                ->withNoArgs()
                ->andReturn(null);
            $context
                ->shouldReceive('getAccountsForGroup')
                ->once()
                ->withArgs([$data['index']])
                ->andReturn([]);
        };

        /** @var \EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext $context */
        $context = $this->getMockWithExpectations(ResultsContext::class, $setExpectations);

        $group = new Group($context, $data);

        self::assertInternalType('array', $group->getAccounts());
        self::assertNull($group->getFile());
        self::assertInstanceOf(Header::class, $group->getHeader());
        self::assertInstanceOf(Trailer::class, $group->getTrailer());
    }
}
