<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack;

use EoneoPay\BankFiles\Helpers\XmlFailureMitigation;
use EoneoPay\BankFiles\Parsers\Ack\Results\Issue;
use EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement;
use EoneoPay\BankFiles\Parsers\BaseParser;
use EoneoPay\Utils\Arr;
use EoneoPay\Utils\Exceptions\InvalidXmlException;
use EoneoPay\Utils\Interfaces\CollectionInterface;
use EoneoPay\Utils\XmlConverter;

abstract class Parser extends BaseParser
{
    /**
     * @var \EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement $acknowledgement
     */
    protected $acknowledgement;

    /**
     * Override parent constructor.
     *
     * @param string $contents
     */
    public function __construct(string $contents)
    {
        parent::__construct($contents);

        $this->process();
    }

    /**
     * Return issues
     *
     * @return \EoneoPay\Utils\Interfaces\CollectionInterface
     */
    public function getIssues(): CollectionInterface
    {
        return $this->acknowledgement->getIssues();
    }

    /**
     * Return PaymentAcknowledgement
     *
     * @return \EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement
     */
    public function getPaymentAcknowledgement(): PaymentAcknowledgement
    {
        return $this->acknowledgement;
    }

    /**
     * Attempts to convert the provided XML string to an array.
     *
     * @param string $xml
     *
     * @return mixed[]|null
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    protected function convertXmlToArray(string $xml): ?array
    {
        $xmlConverter = new XmlConverter();

        try {
            $result = $xmlConverter->xmlToArray($this->contents, 1);
        } catch (InvalidXmlException $exception) {
            // When an exception is thrown, let's attempt to mitigate the issue by cleaning up some common
            // inconsistencies from the bank's side.
            $fixedContents = XmlFailureMitigation::tryMitigateParseFailures($this->contents);

            // If the content back from mitigation is null, throw the initial exception
            if ($fixedContents === null || $fixedContents === '') {
                throw $exception;
            }

            // Run the converter again, this time not capturing any exceptions
            $result = $xmlConverter->xmlToArray($fixedContents, 1);
        }

        return $result;
    }

    /**
     * Determine how to process issues, this array can change depending on whether there
     * are one or many issues to be stored
     *
     * @param mixed $issues
     *
     * @return \EoneoPay\BankFiles\Parsers\Ack\Results\Issue[]
     */
    protected function extractIssues($issues): array
    {
        $arr = new Arr();

        // If there are no issues, return
        if ($issues === null) {
            return [];
        }

        // If issues is a single item, force to array
        if (\array_key_exists('@value', $issues) === true) {
            $issues = [$issues];
        }

        // Process issues array
        $objects = [];
        foreach ($issues as $issue) {
            $objects[] = new Issue([
                'value' => $arr->get($issue, '@value'),
                'attributes' => $arr->get($issue, '@attributes')
            ]);
        }

        return $objects;
    }
}
