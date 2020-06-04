<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack;

use EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement;
use EoneoPay\Utils\Arr;
use EoneoPay\Utils\Collection;

class BpbParser extends Parser
{
    /**
     * Process line and parse data
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    protected function process(): void
    {
        $arr = new Arr();

        $result = $this->convertXmlToArray($this->contents);

        $this->acknowledgement = new PaymentAcknowledgement([
            'attributes' => $arr->get($result, '@attributes'),
            'originalMessageId' => $arr->get($result, 'MessageDetails.OriginalMessageId'),
            'dateTime' => $arr->get($result, 'DateTime'),
            'customerId' => $arr->get($result, 'CustomerId'),
            'companyName' => $arr->get($result, 'CompanyName'),
            'originalFilename' => $arr->get($result, 'MessageDetails.OriginalFilename'),
            'issues' => new Collection($this->extractIssues($arr->get($result, 'Issues.Issue'))),
        ]);
    }
}
