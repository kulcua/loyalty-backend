<?php

namespace Kulcua\Extension\Component\Message\Domain\Event;

use Kulcua\Extension\Component\Message\Domain\MessageId;
use Kulcua\Extension\Component\Message\Domain\Event\MessageEvent;

/**
 * Class MessageWasCreated.
 */
class MessageWasCreated extends MessageEvent
{
    /**
     * @var array
     */
    protected $messageData;

    /**
     * MessageEvent constructor.
     *
     * @param MessageId $messageId
     * @param array         $messageData
     */
    public function __construct(
        MessageId $messageId,
        array $messageData
    ) {
        parent::__construct($messageId);

        if (is_numeric($messageData['messageTimestamp'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($messageData['messageTimestamp']);
            $messageData['messageTimestamp'] = $tmp;
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

    /**
     * @return array
     */
    public function serialize(): array
    {
        $messageData = $this->messageData;

        return array_merge(parent::serialize(), [
            'messageId' => $this->messageId->__toString(),
            'messageData' => $messageData,
        ]);
    }

    public static function deserialize(array $data)
    {
        $messageData = $data['messageData'];

        return new self(
            new MessageId($data['messageId']),
            $messageData
        );
    }
}
