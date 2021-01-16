<?php

namespace Kulcua\Extension\Component\Conversation\Domain\Command;

use Kulcua\Extension\Component\Conversation\Domain\ConversationId;

/**
 * Class UpdateConversation.
 */
class UpdateConversation extends ConversationCommand
{
    /**
     * @var ConversationId
     */
    protected $id;

    /**
     * @var array
     */
    protected $conversationData;

    /**
     * UpdateConversation constructor.
     *
     * @param ConversationId $conversationId
     * @param array   $conversationData
     */
    public function __construct(ConversationId $conversationId, array $conversationData)
    {
        parent::__construct($conversationId);
        $this->conversationData = $conversationData;
    }

    /**
     * @return null|ConversationId
     */
    public function getId(): ?ConversationId
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getConversationData()
    {
        return $this->conversationData;
    }
}
