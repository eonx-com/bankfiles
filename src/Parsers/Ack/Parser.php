<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Ack;

use EoneoPay\BankFiles\Parsers\Ack\Results\Issue;
use EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement;
use EoneoPay\BankFiles\Parsers\BaseParser;
use EoneoPay\Utils\Arr;
use EoneoPay\Utils\XmlConverter;
use Illuminate\Support\Collection;

class Parser extends BaseParser
{
    /** @var PaymentAcknowledgement $acknowledgement */
    private $acknowledgement;

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
     * @return Collection
     */
    public function getIssues(): Collection
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
     */
    protected function process(): void
    {
        $xmlConverter = new XmlConverter();
        $arr = new Arr();

        $result = $xmlConverter->xmlToArray($this->contents, 1);

        $issues = [];
        if ($arr->get($result, 'Issues')) {
            foreach ($result['Issues']['Issue'] as $issue) {
                $issues[] = new Issue([
                    'value' => $arr->get($issue, '@value'),
                    'attributes' => $arr->get($issue, '@attributes'),
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
            'issues' => \collect($issues)
        ]);
    }
}
