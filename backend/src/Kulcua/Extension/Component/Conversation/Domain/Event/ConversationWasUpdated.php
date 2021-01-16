<?php

namespace Kulcua\Extension\Component\Conversation\Domain\Event;

use Kulcua\Extension\Component\Conversation\Domain\ConversationId;

/**
 * Class ConversationWasUpdated.
 */
class ConversationWasUpdated extends ConversationEvent
{
    protected $conversationData;

    public function __construct(ConversationId $conversationId, array $conversationData)
    {
        parent::__construct($conversationId);
        $this->conversationData = $conversationData;
    }

    public function serialize(): array
    {
        $data = $this->conversationData;

        return array_merge(parent::serialize(), array(
            'conversationData' => $data
        ));
    }

    public static function deserialize(array $data)
    {
        return new self(
            new ConversationId($data['conversationId']),
            $data['conversationData']
        );
    }

    /**
     * @return array
     */
    public function getConversationData()
    {
        return $this->conversationData;
    }
}
