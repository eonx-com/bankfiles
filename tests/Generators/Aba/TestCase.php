<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Generators\Aba;

use EoneoPay\BankFiles\Generators\Aba\Objects\DescriptiveRecord;
use EoneoPay\BankFiles\Generators\Aba\Objects\FileTotalRecord;
use EoneoPay\BankFiles\Generators\Aba\Objects\Transaction;
use Tests\EoneoPay\BankFiles\Generators\TestCase as GeneratorTestCase;

class TestCase extends GeneratorTestCase
{
    /**
     * Create a DescriptiveRecord object with default attributes
     *
     * @return DescriptiveRecord
     */
    protected function createDescriptiveRecord(): DescriptiveRecord
    {
        return new DescriptiveRecord([
            'recordType' => '0',
            'blank1' => \str_pad('', 17),
            'reelSequenceNumber' => '01',
            'userFinancialInstitution' => 'UFI',
            'black2' => \str_pad('', 7),
            'nameOfUseSupplyingFile' => 'LOYALTY CORP AUSTRALIA    ',
            'numberOfUseSupplyingFile' => 492627,
            'descriptionOfEntries' => 'PAYMENTS    ',
            'dateToBeProcessed' => '100817',
            'blank3' => \str_pad('', 40)
        ]);
    }

    /**
     * Create File Total Record object with default values
     *
     * @return FileTotalRecord
     */
    protected function createFileTotalRecord(): FileTotalRecord
    {
        return new FileTotalRecord([
            'recordType' => '7',
            'bsbFiller' => '999-999',
            'blank1' => \str_pad('', 12),
            'fileUserNetTotalAmount' => '0000000000',
            'fileUserCreditTotalAmount' => '0000043452',
            'fileUserDebitTotalAmount' => '0000043452',
            'blank2' => \str_pad('', 24),
            'fileUserCountOfRecordsType' => '000002',
            'blank3' => \str_pad('', 40)
        ]);
    }

    /**
     * Create a Transaction object with default values
     *
     * @return Transaction
     */
    protected function createTransaction(): Transaction
    {
        return new Transaction([
            'recordType' => 1,
            'bsbNumber' => '083-163',
            'accountNumberToBeCreditedDebited' => '  1234356',
            'indicator' => ' ',
            'transactionCode' => '50',
            'amount' => '0000043452',
            'titleOfAccountToBeCreditedDebited' => 'TRUST ME                        ',
            'lodgementReference' => '0049e2d7dd1288d086',
            'traceRecord' => '083-170',
            'accountNumber' => '739827524',
            'nameOfRemitter' => 'TEST PAY RENT RE',
            'amountOfWithholdingTax' => '00000000'
        ]);
    }
}
