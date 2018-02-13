<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Ack\Results;

use DateTime;
use EoneoPay\BankFiles\Parsers\Ack\Results\PaymentAcknowledgement;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class PaymentAcknowledgementTest extends TestCase
{
    /**
     * Should return datetime as DateTime object
     *
     * @group Ack-PaymentAcknowledgement
     *
     * @return void
     */
    public function testShouldReturnDateTimeAsObject(): void
    {
        $dateString = '2017/10/17';
        $expected = [
            '@value' => new DateTime($dateString)
        ];

        $paymentAcknowledgement = new PaymentAcknowledgement([
            'dateTime' => ['@value' => $dateString],
        ]);

        self::assertEquals($expected, $paymentAcknowledgement->getDateTime());
    }
}
