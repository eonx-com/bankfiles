<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai;

trait ControlTotal
{
    /**
     * Format amount/total from string to float
     *
     * @param string $amount
     *
     * @return float
     */
    private function formatAmount(string $amount): float
    {
        $length = \strlen($amount);

        return (float)\sprintf(
            '%d.%d',
            (int)\substr($amount, 0, $length - 2),
            (int)\substr($amount, $length - 2)
        );
    }
}
