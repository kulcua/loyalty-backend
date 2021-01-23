<?php

declare(strict_types=1);

namespace Kulcua\Extension\Component\Message\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class AccountId.
 */
class AccountId implements Identifier
{
    /**
     * @var string
     */
    private $accountId;

    /**
     * AccountId constructor.
     *
     * @param string $accountId
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $accountId)
    {
        Assert::string($accountId);
        Assert::uuid($accountId);

        $this->accountId = $accountId;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->accountId;
    }
}
