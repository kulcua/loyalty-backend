<?php

namespace Kulcua\Extension\Component\Conversation\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class ConversationId.
 */
class ConversationId implements Identifier
{
    private $conversationId;

    /**
     * @param string $conversationId
     */
    public function __construct($conversationId)
    {
        Assert::string($conversationId);
        Assert::uuid($conversationId);

        $this->conversationId = $conversationId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->conversationId;
    }
}
