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
            'recordType' => '1',
            'batchCustomerId' => \str_pad('BatchCustomerId', 16),
            'customerShortName' => \str_pad('CustomerShortName', 20),
            'processingDate' => '20171104',
            'restOfRecord' => \str_pad('', 99),
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
            'recordType' => '2',
            'billerCode' => \str_pad('5566778', 10, '0', STR_PAD_LEFT),
            'paymentAccountBSB' => '334455',
            'paymentAccountNumber' => '112233445',
            'customerReferenceNumber' => \str_pad('9457689335', 20),
            'amount' => \str_pad('2599', 13, '0', STR_PAD_LEFT),
            'lodgementReference1' => \str_pad('lodgeRef1', 10),
            'lodgementReference2' => \str_pad('lodgeRef2', 20),
            'lodgementReference3' => \str_pad('lodgeRef1', 50),
            'restOfRecord' => \str_pad('', 5)
        ]);
    }

    protected function createTrailer()
    {
        return new Trailer([
            'recordType' => '9',
            'totalNumberOfPayments' => \str_pad('125', 10, '0', STR_PAD_LEFT),
            'totalFileValue' => \str_pad('1500', 13, '0', STR_PAD_LEFT),
        ]);
    }
}
