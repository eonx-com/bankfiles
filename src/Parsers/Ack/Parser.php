<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack;

use EoneoPay\BankFiles\Parsers\Ack\Results\Issue;
use EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement;
use EoneoPay\BankFiles\Parsers\BaseParser;
use EoneoPay\Utils\Arr;
use EoneoPay\Utils\Interfaces\CollectionInterface;

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
