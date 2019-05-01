<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers;

use Tests\EoneoPay\BankFiles\Parsers\Stubs\ParserStub;

class BaseParserTest extends TestCase
{
    /**
     * Should set the content
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function testShouldSetContent(): void
    {
        $content = 'sample content';

        $parser = new ParserStub($content);

        $property = $this->getProtectedProperty(\get_class($parser), 'contents');

        self::assertSame($content, $property->getValue($parser));
    }
}
