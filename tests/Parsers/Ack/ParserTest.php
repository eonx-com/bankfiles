<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Ack;

use EoneoPay\BankFiles\Parsers\Ack\Parser;
use EoneoPay\BankFiles\Parsers\Ack\Results\Issue;
use EoneoPay\Utils\Collection;
use EoneoPay\Utils\XmlConverter;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class ParserTest extends TestCase
{
    /**
     * Test issues are correctly processed regardless of formatting
     *
     * @group Ack-Parser
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException Inherited, if xml contains an invalid tag
     */
    public function testIssueProcessing(): void
    {
        $converter = new XmlConverter();

        // Test no issues
        $xml = $converter->arrayToXml([]);
        $parser = new Parser($xml);
        self::assertEquals(new Collection(), $parser->getIssues());

        // Test single issue without attributes
        $xml = $converter->arrayToXml(['Issues' => ['Issue' => 'test']]);
        $parser = new Parser($xml);
        self::assertEquals(
            new Collection([new Issue(['value' => 'test', 'attributes' => null])]),
            $parser->getIssues()
        );

        // Test single issue with attribute
        $xml = $converter->arrayToXml(['Issues' => ['Issue' => ['@attributes' => ['id' => '10'], '@value' => 'test']]]);
        $parser = new Parser($xml);
        self::assertEquals(
            new Collection([new Issue(['value' => 'test', 'attributes' => ['id' => '10']])]),
            $parser->getIssues()
        );

        // Test array of issues
        $xml = $converter->arrayToXml([
            'Issues' => [
                'Issue' => [
                    ['@attributes' => ['id' => '10'], '@value' => 'test'],
                    ['@attributes' => ['id' => '11'], '@value' => 'test2']
                ]
            ]
        ]);
        $parser = new Parser($xml);
        self::assertEquals(
            new Collection([
                new Issue(['value' => 'test', 'attributes' => ['id' => '10']]),
                new Issue(['value' => 'test2', 'attributes' => ['id' => '11']])
            ]),
            $parser->getIssues()
        );
    }

    /**
     * Should return empty collection if no issue
     * and an empty array if attribute is not found in the xml
     *
     * @group Ack-Parser
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    public function testShouldReturnIfNoIssues(): void
    {
        $filename = \realpath(__DIR__ . '/data/no_issues_sample.txt.ENC.PROCESSED.ACK');
        $content = \file_get_contents($filename ?: '') ?: '';

        $parser = new Parser($content);

        self::assertInstanceOf(Collection::class, $parser->getIssues());

        // PaymentId is not in the xml
        self::assertNull($parser->getPaymentAcknowledgement()->getPaymentId());
    }

    /**
     * Should return PaymentAcknowledgement, Issues collection and Issue object
     *
     * @group Ack-Parser
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    public function testShouldReturnIssues(): void
    {
        $filename = \realpath(__DIR__ . '/data/sample.txt.ENC.PROCESSED.ACK');
        $content = \file_get_contents($filename ?: '') ?: '';

        $parser = new Parser($content);

        self::assertInstanceOf(Collection::class, $parser->getIssues());
        self::assertInstanceOf(Issue::class, $parser->getIssues()->first());
        self::assertIsArray($parser->getIssues()->first()->getAttributes());

        self::assertInstanceOf(Collection::class, $parser->getPaymentAcknowledgement()->getIssues());
        self::assertInstanceOf(Issue::class, $parser->getPaymentAcknowledgement()->getIssues()->first());
        self::assertIsArray($parser->getPaymentAcknowledgement()->getIssues()->first()->getAttributes());
    }
}
