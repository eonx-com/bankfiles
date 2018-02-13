<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Ack;

use EoneoPay\BankFiles\Parsers\Ack\Parser;
use EoneoPay\BankFiles\Parsers\Ack\Results\Issue;
use EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement;
use Illuminate\Support\Collection;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class ParserTest extends TestCase
{
    /**
     * Should return PaymentAcknowledgement, Issues collection and Issue object
     *
     * @group Ack-Parser
     *
     * @return void
     */
    public function testShouldReturnIssues(): void
    {
        $filename = \realpath(__DIR__ . '/data/sample.txt.ENC.PROCESSED.ACK');
        $content = \file_get_contents($filename);

        $parser = new Parser($content);

        self::assertInstanceOf(Collection::class, $parser->getIssues());
        self::assertInstanceOf(Issue::class, $parser->getIssues()->first());
        self::assertInternalType('array', $parser->getIssues()->first()->getAttributes());

        self::assertInstanceOf(PaymentAcknowledgement::class, $parser->getPaymentAcknowledgement());
        self::assertInstanceOf(Collection::class, $parser->getPaymentAcknowledgement()->getIssues());
        self::assertInstanceOf(Issue::class, $parser->getPaymentAcknowledgement()->getIssues()->first());
        self::assertInternalType('array', $parser->getPaymentAcknowledgement()->getIssues()->first()->getAttributes());
    }

    /**
     * Should return empty collection if no issue
     * and an empty array if attribute is not found in the xml
     *
     * @group Ack-Parser
     *
     * @return void
     */
    public function testShouldReturnIfNoIssues(): void
    {
        $filename = \realpath(__DIR__ . '/data/no_issues_sample.txt.ENC.PROCESSED.ACK');
        $content = \file_get_contents($filename);

        $parser = new Parser($content);

        self::assertInstanceOf(Collection::class, $parser->getIssues());

        // PaymentId is not in the xml
        self::assertNull($parser->getPaymentAcknowledgement()->getPaymentId());
        self::assertInternalType('null', $parser->getPaymentAcknowledgement()->getPaymentId());
    }
}
