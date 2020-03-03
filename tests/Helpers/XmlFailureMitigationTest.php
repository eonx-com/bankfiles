<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Helpers;

use EoneoPay\BankFiles\Helpers\XmlFailureMitigation;
use Tests\EoneoPay\BankFiles\TestCases\TestCase;

/**
 * @covers \EoneoPay\BankFiles\Helpers\XmlFailureMitigation
 *
 * @SuppressWarnings(PHPMD.StaticAccess) Ignore static access to XML mitigation.
 */
class XmlFailureMitigationTest extends TestCase
{
    /**
     * Gets the XML scenarios for testing.
     *
     * @return mixed[]
     */
    public function getXmlScenarios(): iterable
    {
        yield 'HTML-like characters in node value' => [
            'input' => <<<'XML'
<PaymentsAcknowledgement type="warning">
    <Issues>
        <Issue type="test">This is a very <1> important <test> issue.</Issue>
    </Issues>
</PaymentsAcknowledgement>
XML,
            'expected' => <<<'XML'
<PaymentsAcknowledgement type="warning">
    <Issues>
        <Issue type="test">This is a very &lt;1&gt; important &lt;test&gt; issue.</Issue>
    </Issues>
</PaymentsAcknowledgement>
XML
        ];
    }

    /**
     * Test that the helper class does not touch valid XML.
     *
     * @return void
     */
    public function testMitigationLeavesValidXmlAlone(): void
    {
        // phpcs:disable
        // Disabled to ignore long lines in XML sample.
        $xml = <<<'XML'
<PaymentsAcknowledgement type="info">
<PaymentId>94829970</PaymentId>
<OriginalMessageId>94829954</OriginalMessageId>
<DateTime>2017/10/17</DateTime>
<CustomerId>LOYC01AU</CustomerId>
<CompanyName>Loyalty Corp Australia Pty Ltd</CompanyName>
<UserMessage>Payment status is PROCESSED WITH INVALID TRANSACTIONS</UserMessage>
<DetailedMessage>Payment has been successfully processed and invalid items have been returned to your account.</DetailedMessage>
<OriginalFilename>credit-mer_584aaa43110d77d1b224c20a20171016_221504.txt.ENC</OriginalFilename>
<OriginalReference>Encrypted file</OriginalReference>
<Issues>
<Issue type="2025">Payment 105205350 successfully uploaded from a file.</Issue>
<Issue type="2025">Payment 105205350 successfully uploaded from a file.</Issue>
<Issue type="104503">Payment successfully validated.</Issue>
<Issue type="181301">Payment is ready to be submitted for processing.</Issue>
</Issues>
</PaymentsAcknowledgement>
XML;
        // phpcs:enable

        $result = XmlFailureMitigation::tryMitigateParseFailures($xml);

        self::assertSame($xml, $result);
    }

    /**
     * Tests that the helper method successfully handles the provided scenarios.
     *
     * @param string $input
     * @param string $expected
     *
     * @return void
     *
     * @dataProvider getXmlScenarios
     */
    public function testMitigationReplacesInvalidLines(string $input, string $expected): void
    {
        $result = XmlFailureMitigation::tryMitigateParseFailures($input);

        self::assertSame($expected, $result);
    }
}
