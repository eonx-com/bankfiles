<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers;

use Tests\EoneoPay\BankFiles\Parsers\Stubs\StubParser;

class BaseParserTest extends TestCase
{
    /**
     * Should set the content
     *
     * @group Base-Parser
     *
     * @return void
     */
    public function testShouldSetContent(): void
    {
        $content = 'sample content';

        $parser = new StubParser($content);

        $property = $this->getProtectedProperty(\get_class($parser), 'contents');

        self::assertEquals($content, $property->getValue($parser));
    }
}
