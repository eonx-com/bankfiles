<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Bpay;

use EoneoPay\BankFiles\Generators\Bpay\Objects\Header;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Trailer;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Transaction;
use Tests\EoneoPay\BankFiles\Generators\TestCase as GeneratorTestCase;

class TestCase extends GeneratorTestCase
{
    /**
     * Create a Header object
     *
     * @return Header
     */
    protected function createHeader(): Header
    {
        return new Header([
            'batchCustomerId' => 'BatchCustomerId',
            'customerShortName' => 'CustomerShortName',
            'processingDate' => '20171104'
        ]);
    }

    /**
     * Create a Transaction object
     *
     * @return Transaction
     */
    protected function createTransaction(): Transaction
    {
        return new Transaction([
            'billerCode' => '5566778',
            'paymentAccountBSB' => '334455',
            'paymentAccountNumber' => '112233445',
            'customerReferenceNumber' => '9457689335',
            'amount' => '2599',
            'lodgementReference1' => 'lodgeRef1',
            'lodgementReference2' => 'lodgeRef2',
            'lodgementReference3' => 'lodgeRef1'
        ]);
    }

    /**
     * Create a Trailer object.
     *
     * @return \EoneoPay\BankFiles\Generators\Bpay\Objects\Trailer
     */
    protected function createTrailer(): Trailer
    {
        return new Trailer([
            'totalNumberOfPayments' => '125',
            'totalFileValue' => '1500'
        ]);
    }
}
