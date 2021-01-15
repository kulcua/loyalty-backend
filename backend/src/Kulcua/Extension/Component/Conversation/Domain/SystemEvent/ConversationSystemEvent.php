<?php

namespace Kulcua\Extension\Component\Conversation\Domain\SystemEvent;

use Kulcua\Extension\Component\Conversation\Domain\ConversationId;

/**
 * Class ConversationSystemEvent.
 */
class ConversationSystemEvent
{
    /**
     * @var ConversationId
     */
    protected $conversationId;

    /**
     * ConversationSystemEvent constructor.
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
}
