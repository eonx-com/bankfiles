<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack;

use EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement;
use EoneoPay\Utils\Arr;
use EoneoPay\Utils\Collection;
use EoneoPay\Utils\XmlConverter;

class BpbParser extends Parser
{
    /**
     * Process line and parse data
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    protected function process(): void
    {
        $xmlConverter = new XmlConverter();
        $arr = new Arr();

        $result = $xmlConverter->xmlToArray($this->contents, 1);

        $this->acknowledgement = new PaymentAcknowledgement([
            'attributes' => $arr->get($result, '@attributes'),
            'originalMessageId' => $arr->get($result, 'MessageDetails.OriginalMessageId'),
            'dateTime' => $arr->get($result, 'DateTime'),
            'customerId' => $arr->get($result, 'CustomerId'),
            'companyName' => $arr->get($result, 'CompanyName'),
            'originalFilename' => $arr->get($result, 'MessageDetails.OriginalFilename'),
            'issues' => new Collection($this->extractIssues($arr->get($result, 'Issues.Issue')))
        ]);
    }
}
