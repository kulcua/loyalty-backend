<?php

namespace Kulcua\Extension\Component\Message\Domain\Event;

use Broadway\Serializer\Serializable;
use Kulcua\Extension\Component\Message\Domain\MessageId;

/**
 * Class MessageEvent.
 */
abstract class MessageEvent implements Serializable
{
    /**
     * @var MessageId
     */
    protected $messageId;

    /**
     * MessageEvent constructor.
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

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'messageId' => $this->messageId->__toString(),
        ];
    }
}