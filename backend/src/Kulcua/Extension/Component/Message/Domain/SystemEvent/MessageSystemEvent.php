<?php

namespace Kulcua\Extension\Component\Message\Domain\SystemEvent;

use Kulcua\Extension\Component\Message\Domain\MessageId;

/**
 * Class MessageSystemEvent.
 */
class MessageSystemEvent
{
    /**
     * @var MessageId
     */
    protected $messageId;

    /**
     * MessageSystemEvent constructor.
     *
     * @param MessageId $messageId
     */
    public function __construct(MessageId $messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @return MessageId
     */
    public function getMessageId()
    {
        return $this->messageId;
    }
}
