<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles;

use EoneoPay\BankFiles\Generators\Bpay\Generator;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Header;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Trailer;
use EoneoPay\BankFiles\Generators\Bpay\Objects\Transaction;
use Tests\EoneoPay\BankFiles\Generators\Bpay\TestCase;

class SampleFilesTest extends TestCase
{
    public function testGenerateRandomSample(): void
    {
        $header = $this->createHeader();

        // create a transaction and set it's values
        $trans1 =  $this->createTransaction();
        $trans1
            ->setAttribute('billerCode', '11133')
            ->setAttribute('amount', '200');
        $trans2 =  $this->createTransaction();

        $trailer = $this->createTrailer();

        $generator = new Generator($header, [$trans1, $trans2], $trailer);

        $sample = \fopen('random_sample.bpb', 'wb+');
        \fwrite($sample, $generator->getContents());
        \fclose($sample);

        self::assertTrue(true);
    }

    public function testGenerateOfficialSample(): void
    {
        $header = new Header([
            'batchCustomerId' => 'custid',
            'customerShortName' => 'Customer short name',
            'processingDate' => '20060126'
        ]);

        $transactions = [];
        $transactions[] = new Transaction([
            'billerCode' => '1',
            'paymentAccountBSB' => '083001',
            'paymentAccountNumber' => '999999999',
            'customerReferenceNumber' => '5353999999999999',
            'amount' => '100',
            'lodgementReference1' => 'lodge ref1',
            'lodgementReference2' => 'lodgement reference2',
            'lodgementReference3' => 'lodgement reference 333333333333333333333333333333'
        ]);
        $transactions[] = new Transaction([
            'billerCode' => '2',
            'paymentAccountBSB' => '083002',
            'paymentAccountNumber' => '888888888',
            'customerReferenceNumber' => '5353888888888888',
            'amount' => '200',
            'lodgementReference1' => 'lodge ref1',
            'lodgementReference2' => 'lodgement reference2',
            'lodgementReference3' => 'lodgement reference 333333333333333333333333333333'
        ]);
        $transactions[] = new Transaction([
            'billerCode' => '3',
            'paymentAccountBSB' => '083003',
            'paymentAccountNumber' => '777777777',
            'customerReferenceNumber' => '5353777777777777',
            'amount' => '300',
            'lodgementReference1' => 'lodge ref1',
            'lodgementReference2' => 'lodgement reference2',
            'lodgementReference3' => 'lodgement reference 333333333333333333333333333333'
        ]);
        $transactions[] = new Transaction([
            'billerCode' => '4',
            'paymentAccountBSB' => '083004',
            'paymentAccountNumber' => '666666666',
            'customerReferenceNumber' => '5353666666666666',
            'amount' => '400',
            'lodgementReference1' => 'lodge ref1',
            'lodgementReference2' => 'lodgement reference2',
            'lodgementReference3' => 'lodgement reference 333333333333333333333333333333'
        ]);
        $transactions[] = new Transaction([
            'billerCode' => '5',
            'paymentAccountBSB' => '083005',
            'paymentAccountNumber' => '555555555',
            'customerReferenceNumber' => '5353555555555555',
            'amount' => '500',
            'lodgementReference1' => 'lodge ref1',
            'lodgementReference2' => 'lodgement reference2',
            'lodgementReference3' => 'lodgement reference 333333333333333333333333333333'
        ]);

        $trailer = new Trailer([
            'totalNumberOfPayments' => '5',
            'totalFileValue' => '1500'
        ]);

        $generator = new Generator($header, $transactions, $trailer);

        $sample = \fopen('official_sample.bpb', 'wb+');
        \fwrite($sample, $generator->getContents());
        \fclose($sample);

        self::assertTrue(true);
    }
}
