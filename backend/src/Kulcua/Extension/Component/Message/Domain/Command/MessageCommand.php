<?php

namespace Kulcua\Extension\Component\Message\Domain\Command;

use Kulcua\Extension\Component\Message\Domain\MessageId;

/**
 * Class MessageCommand.
 */
abstract class MessageCommand
{
    /**
     * @var MessageId
     */
    protected $messageId;

    /**
     * MessageCommand constructor.
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
