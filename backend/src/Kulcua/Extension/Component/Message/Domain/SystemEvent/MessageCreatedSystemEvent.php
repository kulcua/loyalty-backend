<?php

namespace Kulcua\Extension\Component\Message\Domain\SystemEvent;

use Kulcua\Extension\Component\Message\Domain\MessageId;

/**
 * Class MessageCreatedSystemEvent.
 */
class MessageCreatedSystemEvent
{
    /**
     * @var MessageId
     */
    protected $messageId;

    /**
     * @var array
     */
    protected $messageData;

    /**
     * MessageBookedSystemEvent constructor.
     *
     * @param MessageId $messageId
     * @param array         $messageData
     */
    public function __construct(MessageId $messageId, array $messageData)
    {
        $this->messageId = $messageId;
        $this->messageData = $messageData;
    }

    /**
     * @return MessageId
     */
    public function getMessageId(): MessageId
    {
        return $this->messageId;
    }

    /**
     * @return array
     */
    public function getMessageData(): array
    {
        return $this->messageData;
    }
}
