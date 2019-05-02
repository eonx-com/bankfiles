<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Ack\Results;

use EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement;
use EoneoPay\Utils\DateTime;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class PaymentAcknowledgementTest extends TestCase
{
    /**
     * Should return datetime as DateTime object
     *
     * @group Ack-PaymentAcknowledgement
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException If datetime constructor string is invalid
     */
    public function testShouldReturnDateTimeAsObject(): void
    {
        $dateString = '2017/10/17';
        $expected = [
            '@value' => new DateTime($dateString)
        ];

        $acknowledgement = new PaymentAcknowledgement([
            'dateTime' => ['@value' => $dateString]
        ]);

        self::assertEquals($expected, $acknowledgement->getDateTime());
    }
}
