<?php

namespace Kulcua\Extension\Component\Message\Domain\Command;

use Kulcua\Extension\Component\Message\Domain\MessageId;
use Assert\Assertion as Assert;

/**
 * Class CreateMessage.
 */
class CreateMessage extends MessageCommand
{
    /**
     * @var array
     */
    protected $messageData;

    private $requiredMessageFields = [
        'conversationId',
        'conversationParticipantIds',
        'senderId',
        'senderName',
        'message',
        'messageTimestamp'
    ];

    public function __construct(
        MessageId $messageId,
        array $messageData
    ) {
        parent::__construct($messageId);
        foreach ($this->requiredMessageFields as $field) {
            Assert::keyExists($messageData, $field);
        }

        $this->messageData = $messageData;
    }

    /**
     * @return array
     */
    public function getMessageData()
    {
        return $this->messageData;
    }
}
