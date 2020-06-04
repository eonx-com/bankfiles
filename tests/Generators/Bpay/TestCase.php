<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Bpay;

use EoneoPay\BankFiles\Generators\Bpay\Objects\Header;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Transaction;
use Tests\EoneoPay\BankFiles\Generators\TestCase as GeneratorTestCase;

class TestCase extends GeneratorTestCase
{
    /**
     * Create a Header object
     */
    protected function createHeader(): Header
    {
        return new Header([
            'batchCustomerId' => '85765',
            'customerShortName' => 'CustomerShortName',
            'processingDate' => '20171104',
        ]);
    }

    /**
     * Create a Transaction object
     */
    protected function createTransaction(): Transaction
    {
        return new Transaction([
            'billerCode' => '5566778',
            'paymentAccountBSB' => '084455',
            'paymentAccountNumber' => '112233445',
            'customerReferenceNumber' => '9457689335',
            'amount' => '2599',
            'lodgementReference1' => 'lodgeRef1',
            'lodgementReference2' => 'lodgeRef2',
            'lodgementReference3' => 'lodgeRef2',
        ]);
    }
}
