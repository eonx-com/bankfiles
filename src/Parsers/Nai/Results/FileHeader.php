<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use DateTime;
use EoneoPay\BankFiles\Parsers\BaseResult;

/**
 * @method getReceiverId
 * @method getFileSequenceNumber
 * @method getPhysicalRecordLength
 * @method getBlockingFactor
 * @method getFileCreationTime
 */
class FileHeader extends BaseResult
{
    /** @var array $attributes */
    protected $attributes = [
        'code',
        'senderId',
        'receiverId',
        'fileCreationDate',
        'fileCreationTime',
        'fileSequenceNumber',
        'physicalRecordLength',
        'blockingFactor'
    ];

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
}
