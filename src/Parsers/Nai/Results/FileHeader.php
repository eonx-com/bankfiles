<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use DateTime;
use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method string getBlockingFactor()
 * @method string getCode()
 * @method string getFileCreationTime()
 * @method string getFileSequenceNumber()
 * @method string getPhysicalRecordLength()
 * @method string getReceiverId()
 * @method string getSenderId()
 */
class FileHeader extends BaseResult
{
    /**
     * Convert to DateTime object and return
     *
     * @return DateTime
     */
    public function getFileCreationDate(): DateTime
    {
        $date = DateTime::createFromFormat('ymd', $this->data['fileCreationDate']);
        $date->setTime(0, 0);

        return $date;
    }

    /**
     * Return object attributes.
     *
     * @return array
     */
    protected function initAttributes(): array
    {
        return [
            'code',
            'senderId',
            'receiverId',
            'fileCreationDate',
            'fileCreationTime',
            'fileSequenceNumber',
            'physicalRecordLength',
            'blockingFactor'
        ];
    }
}
