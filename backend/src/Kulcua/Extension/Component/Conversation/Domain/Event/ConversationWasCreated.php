<?php

namespace Kulcua\Extension\Component\Conversation\Domain\Event;

use Kulcua\Extension\Component\Conversation\Domain\ConversationId;
use Kulcua\Extension\Component\Conversation\Domain\Event\ConversationEvent;

/**
 * Class ConversationWasCreated.
 */
class ConversationWasCreated extends ConversationEvent
{
    /**
     * @var array
     */
    protected $conversationData;

    /**
     * ConversationEvent constructor.
     *
     * @param ConversationId $conversationId
     * @param array         $conversationData
     */
    public function __construct(
        ConversationId $conversationId,
        array $conversationData
    ) {
        parent::__construct($conversationId);

        if (is_numeric($conversationData['lastMessageTimestamp'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($conversationData['lastMessageTimestamp']);
            $conversationData['lastMessageTimestamp'] = $tmp;
        }

        $this->conversationData = $conversationData;
    }

    /**
     * @return array
     */
    public function getConversationData()
    {
        return $this->conversationData;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        $conversationData = $this->conversationData;

        return array_merge(parent::serialize(), [
            'conversationId' => $this->conversationId->__toString(),
            'conversationData' => $conversationData,
        ]);
    }

    public static function deserialize(array $data)
    {
        $conversationData = $data['conversationData'];

        return new self(
            new ConversationId($data['conversationId']),
            $conversationData
        );
    }
}
