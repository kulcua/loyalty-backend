<?php

namespace Kulcua\Extension\Component\Conversation\Domain\Command;

use Kulcua\Extension\Component\Conversation\Domain\ConversationId;

/**
 * Class ConversationCommand.
 */
abstract class ConversationCommand
{
    /**
     * @var ConversationId
     */
    protected $conversationId;

    /**
     * ConversationCommand constructor.
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
