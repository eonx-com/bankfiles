<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\Nai\Parser;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Identifier;
use EoneoPay\BankFiles\Parsers\Nai\Results\Accounts\Trailer;
use Illuminate\Support\Collection;
use Tests\EoneoPay\BankFiles\Parsers\TestCase;

class AccountTest extends TestCase
{
    /**
     * Should return account identifier object and collection of transactions
     *
     * @group Nai-Account-Identifier
     *
     * @return void
     */
    public function testShouldReturnAccountIdentifier(): void
    {
        $filename = \realpath(__DIR__ . '/../data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);
        $account = $parser->getAccounts()->last();

        self::assertInstanceOf(Identifier::class, $account->getIdentifier());
        self::assertInstanceOf(Collection::class, $account->getIdentifier()->getTransactionCodes());
    }

    /**
     * Should return account trailer
     *
     * @group Nai-Account-Trailer
     *
     * @return void
     */
    public function testShouldReturnAccountTrailer(): void
    {
        $filename = \realpath(__DIR__ . '/../data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);
        $account = $parser->getAccounts()->last();

        self::assertInstanceOf(Trailer::class, $account->getTrailer());
    }

    /**
     * Should return account transactions in array data type
     *
     * @group Nai-Account-Transactions
     *
     * @return void
     */
    public function testShouldReturnAccountTransactions(): void
    {
        $filename = \realpath(__DIR__ . '/../data') . '/sample.NAI';
        $content = \file_get_contents($filename);

        $parser = new Parser($content);
        $account = $parser->getAccounts()->last();

        self::assertInstanceOf(Collection::class, $account->getTransactions());
    }
}
