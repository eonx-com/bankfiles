<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results\Files;

use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method string getBlockingFactor()
 * @method string getCode()
 * @method string getFileCreationDate()
 * @method string getFileCreationTime()
 * @method string getFileSequenceNumber()
 * @method string getPhysicalRecordLength()
 * @method string getReceiverId()
 * @method string getSenderId()
 */
class Header extends BaseResult
{
    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return [
            'blockingFactor',
            'code',
            'fileCreationDate',
            'fileCreationTime',
            'fileSequenceNumber',
            'physicalRecordLength',
            'receiverId',
            'senderId'
        ];
    }
}
