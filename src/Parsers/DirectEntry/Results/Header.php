<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\DirectEntry\Results;

use DateTime as BaseDateTime;
use EoneoPay\BankFiles\Parsers\BaseResult;
use EoneoPay\Utils\DateTime;

/**
 * @method string|null getDescription()
 * @method string|null getUserFinancialInstitution()
 * @method string|null getUserIdSupplyingFile()
 * @method string|null getUserSupplyingFile()
 * @method string|null getReelSequenceNumber()
 */
class Header extends BaseResult
{
    /**
     * Get processed date
     *
     * @return \DateTime|null
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function getDateProcessed(): ?BaseDateTime
    {
        if (\is_string($this->data['dateProcessed']) === true &&
            \strlen($this->data['dateProcessed']) === 6
        ) {
            $stringDate = \sprintf(
                '%s-%s-%s',
                \substr($this->data['dateProcessed'], 4, 2),
                \substr($this->data['dateProcessed'], 2, 2),
                \substr($this->data['dateProcessed'], 0, 2)
            );

            return new DateTime($stringDate);
        }

        return null;
    }

    /**
     * Return object attributes.
     *
     * @return string[]
     */
    protected function initAttributes(): array
    {
        return [
            'dateProcessed',
            'description',
            'userFinancialInstitution',
            'userIdSupplyingFile',
            'userSupplyingFile',
            'reelSequenceNumber'
        ];
    }
}
