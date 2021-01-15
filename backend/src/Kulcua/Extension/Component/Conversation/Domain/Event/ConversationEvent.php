<?php

namespace Kulcua\Extension\Component\Conversation\Domain\Event;

use Broadway\Serializer\Serializable;
use Kulcua\Extension\Component\Conversation\Domain\ConversationId;

/**
 * Class ConversationEvent.
 */
abstract class ConversationEvent implements Serializable
{
    /**
     * @var ConversationId
     */
    protected $conversationId;

    /**
     * ConversationEvent constructor.
     *
     * @param ConversationId $conversationId
     */
    public function __construct(ConversationId $conversationId)
    {
        $this->conversationId = $conversationId;
    }

    /**
     * @return ConversationId
     */
    public function getConversationId()
    {
        return $this->conversationId;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'conversationId' => $this->conversationId->__toString(),
        ];
    }
}