<?php
declare(strict_types=1);

namespace EoneoPay\BankFiles\Parsers\Nai\Results;

use EoneoPay\BankFiles\Parsers\BaseResult;

abstract class AbstractNaiResult extends BaseResult
{
    /**
     * @var \EoneoPay\BankFiles\Parsers\Nai\Results\ResultsContext
     */
    protected $context;

    /**
     * AbstractNaiResult constructor.
     *
     * @param mixed[]|null $data
     */
    public function __construct(ResultsContext $context, ?array $data = null)
    {
        parent::__construct($data);

        $this->context = $context;
    }
}
