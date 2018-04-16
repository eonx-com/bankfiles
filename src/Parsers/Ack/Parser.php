<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack;

use EoneoPay\BankFiles\Parsers\Ack\Results\Issue;
use EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement;
use EoneoPay\BankFiles\Parsers\BaseParser;
use EoneoPay\Utils\Arr;
use EoneoPay\Utils\Collection;
use EoneoPay\Utils\Interfaces\CollectionInterface;
use EoneoPay\Utils\XmlConverter;

class Parser extends BaseParser
{
    /** @var PaymentAcknowledgement $acknowledgement */
    private $acknowledgement;

    /**
     * Override parent constructor.
     *
     * @param string $contents
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
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
     * @return PaymentAcknowledgement
     */
    public function getPaymentAcknowledgement(): PaymentAcknowledgement
    {
        return $this->acknowledgement;
    }

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

        $issues = [];
        if ($arr->get($result, 'Issues')) {
            foreach ((array)$result['Issues']['Issue'] as $issue) {
                $issues[] = new Issue([
                    'value' => $arr->get($issue, '@value'),
                    'attributes' => $arr->get($issue, '@attributes')
                ]);
            }
        }

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
            'issues' => new Collection($issues)
        ]);
    }
}
