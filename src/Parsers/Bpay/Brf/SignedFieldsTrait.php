<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Bpay\Brf;

trait SignedFieldsTrait
{
    /** @var array $signedFields */
    private $signedFields = [
        '{' => '0+',
        '}' => '0-',
        'A' => '1+',
        'B' => '2+',
        'C' => '3+',
        'D' => '4+',
        'E' => '5+',
        'F' => '6+',
        'G' => '7+',
        'H' => '8+',
        'I' => '9+',
        'J' => '1-',
        'K' => '2-',
        'L' => '3-',
        'M' => '4-',
        'N' => '5-',
        'O' => '6-',
        'P' => '7-',
        'Q' => '8-',
        'R' => '9-'
    ];

    /**
     * Get the signed field value and type based on the code/key
     *
     * @param string $code
     *
     * @return array|null
     */
    public function getSignedFieldValue(string $code): ?array
    {
        $signedField = $this->signedFields[$code] ?? null;

        if (!$signedField) {
            return null;
        }

        return [
            'value' => \substr($signedField, 0, 1),
            'type' => \substr($signedField, 1) === '+' ? 'credit' : 'debit'
        ];
    }
}
