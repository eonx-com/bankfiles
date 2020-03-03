<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack;

use EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement;
use EoneoPay\Utils\Arr;
use EoneoPay\Utils\Collection;

class AbaParser extends Parser
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
        $arr = new Arr();

        $result = $this->convertXmlToArray($this->contents);

        $this->acknowledgement = new PaymentAcknowledgement([
            'attributes' => $arr->get($result, '@attributes'),
            'paymentId' => $arr->get($result, 'PaymentId'),
            'originalMessageId' => $arr->get($result, 'OriginalMessageId'),
            'dateTime' => $arr->get($result, 'DateTime'),
            'customerId' => $arr->get($result, 'CustomerId'),
            'companyName' => $arr->get($result, 'CompanyName'),
            'userMessage' => $arr->get($result, 'UserMessage'),
            'detailedMessage' => $arr->get($result, 'DetailedMessage'),
            'originalFilename' => $arr->get($result, 'OriginalFilename'),
            'originalReference' => $arr->get($result, 'OriginalReference'),
            'issues' => new Collection($this->extractIssues($arr->get($result, 'Issues.Issue')))
        ]);
    }
}
