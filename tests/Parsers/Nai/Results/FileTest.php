<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Results\File;
use EoneoPay\BankFiles\Parsers\Nai\Results\Files\Header;
use EoneoPay\BankFiles\Parsers\Nai\Results\Files\Trailer;
use EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext;
use Mockery\MockInterface;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Parsers\Nai\Results\File
 */
class FileTest extends TestCase
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
            'trailer' => new Trailer()
        ];

        /** @var \EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext $context */
        $context = $this->getMockWithExpectations(ResultsContext::class, function (MockInterface $context): void {
            $context
                ->shouldReceive('getGroups')
                ->once()
                ->withNoArgs()
                ->andReturn([]);
        });

        $file = new File($context, $data);

        self::assertInstanceOf(Header::class, $file->getHeader());
        self::assertInternalType('array', $file->getGroups());
        self::assertInstanceOf(Trailer::class, $file->getTrailer());
    }
}
