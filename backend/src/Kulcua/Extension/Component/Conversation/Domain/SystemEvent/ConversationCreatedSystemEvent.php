<?php

namespace Kulcua\Extension\Component\Conversation\Domain\SystemEvent;

use Kulcua\Extension\Component\Conversation\Domain\ConversationId;

/**
 * Class ConversationCreatedSystemEvent.
 */
class ConversationCreatedSystemEvent
{
    /**
     * @var ConversationId
     */
    protected $conversationId;

    /**
     * @var array
     */
    protected $conversationData;

    /**
     * ConversationBookedSystemEvent constructor.
     *
     * @param ConversationId $conversationId
     * @param array         $conversationData
     */
    public function __construct(ConversationId $conversationId, array $conversationData, array $customerData)
    {
        $this->conversationId = $conversationId;
        $this->conversationData = $conversationData;
    }

    /**
     * @return ConversationId
     */
    public function getConversationId(): ConversationId
    {
        return $this->conversationId;
    }

    /**
     * @return array
     */
    public function getConversationData(): array
    {
        return $this->conversationData;
    }
}
