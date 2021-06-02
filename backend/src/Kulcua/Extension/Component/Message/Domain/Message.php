<?php

namespace Kulcua\Extension\Component\Message\Domain;

use OpenLoyalty\Component\Core\Domain\SnapableEventSourcedAggregateRoot;
use Kulcua\Extension\Component\Message\Domain\Event\MessageWasCreated;
use Kulcua\Extension\Component\Message\Domain\Event\MessageWasUpdated;
use Kulcua\Extension\Component\Message\Domain\Model\CustomerBasicData;

/**
 * Class Message.
 */
class Message extends SnapableEventSourcedAggregateRoot
{
    /**
     * @var MessageId
     */
    protected $messageId;

    /**
     * @var string
     */
    protected $conversationId;

     /**
     * @var AccountId[]
     */
    protected $conversationParticipantIds;

    /**
     * @var string
     */
    protected $senderId;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->messageId;
    }

    /**
     * @param MessageId $messageId
     * @param array         $messageData
     *
     * @return Message
     */
    public static function createMessage(
        MessageId $messageId,
        array $messageData
    ): Message {
        $message = new self();
        $message->create(
            $messageId,
            $messageData
        );

        return $message;
    }

    /**
     * @param MessageId $messageId
     * @param array         $messageData
     * @param array         $customerData
     */
    private function create(
        MessageId $messageId,
        array $messageData
    ): void {
        $this->apply(
            new MessageWasCreated(
                $messageId,
                $messageData
            )
        );
    }

    //In order to find all listeners which are listening for this event, 
    //you have to find all services with tag broadway.domain.event_listener 
    //and with this method
    /**
     * @param MessageWasCreated $event
     */
    protected function applyMessageWasCreated(MessageWasCreated $event): void
    {
        $messageData = $event->getMessageData();
        $this->messageId = $event->getMessageId();
        $this->conversationId = $messageData['conversationId'];
        $this->conversationParticipantIds = $messageData['conversationParticipantIds'];
        $this->senderId = $messageData['senderId'];
        $this->senderName = $messageData['senderName'];
        $this->message = $messageData['message'];
        $this->messageTimestamp = $messageData['messageTimestamp'];
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->getMessageId();
    }

    /**
     * @return MessageId
     */
    public function getMessageId(): MessageId
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getConversationId(): string
    {
        return $this->conversationId;
    }

    /**
     * @return AccountId[]
     */
    public function getConversationParticipantIds(): array
    {
        return $this->conversationParticipantIds;
    }

    /**
     * @return string
     */
    public function getSenderId(): string
    {
        return $this->senderId;
    }

    /**
     * @return string
     */
    public function getSenderName(): string
    {
        return $this->senderName;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return \DateTime
     */
    public function getLastMessageTimestamp(): \DateTime
    {
        return $this->lastMessageTimestamp;
    }

    // public function setSenderId(string $senderId)
    // {
    //     return $this->senderId = $senderId;
    // }

    // public function setMessage(string $message)
    // {
    //     return $this->message = $message;
    // }

    // public function setLastMessageTimestamp(DateTime $time)
    // {
    //     return $this->lastMessageTimestamp = $time;
    // }
}