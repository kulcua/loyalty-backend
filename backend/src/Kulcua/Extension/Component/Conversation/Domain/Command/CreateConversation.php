<?php

namespace Kulcua\Extension\Component\Conversation\Domain\Command;

use Kulcua\Extension\Component\Conversation\Domain\ConversationId;
use Assert\Assertion as Assert;

/**
 * Class CreateConversation.
 */
class CreateConversation extends ConversationCommand
{
    /**
     * @var array
     */
    protected $conversationData;

    private $requiredConversationFields = [
        'participantIds',
        'participantNames',
        'lastMessageSnippet',
        'lastMessageTimestamp'
    ];

    public function __construct(
        ConversationId $conversationId,
        array $conversationData
    ) {
        parent::__construct($conversationId);
        foreach ($this->requiredConversationFields as $field) {
            Assert::keyExists($conversationData, $field);
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
}
