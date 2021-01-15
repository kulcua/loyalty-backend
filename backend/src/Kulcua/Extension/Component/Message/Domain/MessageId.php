<?php

namespace Kulcua\Extension\Component\Message\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class MessageId.
 */
class MessageId implements Identifier
{
    private $messageId;

    /**
     * @param string $messageId
     */
    public function __construct($messageId)
    {
        Assert::string($messageId);
        Assert::uuid($messageId);

        $this->messageId = $messageId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->messageId;
    }
}
