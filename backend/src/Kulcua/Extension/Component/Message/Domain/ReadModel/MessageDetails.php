<?php

namespace Kulcua\Extension\Component\Message\Domain\ReadModel;

use Broadway\ReadModel\SerializableReadModel;
use OpenLoyalty\Component\Core\Domain\ReadModel\Versionable;
use OpenLoyalty\Component\Core\Domain\ReadModel\VersionableReadModel;
use Kulcua\Extension\Component\Message\Domain\Message;
use Kulcua\Extension\Component\Message\Domain\MessageId;

/**
 * Class MessageDetails.
 */
class MessageDetails implements SerializableReadModel, VersionableReadModel
{
    use Versionable;

    /**
     * @var MessageId
     */
    protected $messageId;

    /**
     * @var string
     */
    protected $conversationId;

    /**
     * @var array
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
    protected $messageTimestamp;

    /**
     * MessageDetails constructor.
     *
     * @param MessageId $messageId
     */
    public function __construct(MessageId $messageId)
    {
        $this->messageId = $messageId;
    }


    /**
     * @param array $data
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    { 
        $message = new self(new MessageId($data['messageId']));

        if (is_numeric($data['messageTimestamp'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($data['messageTimestamp']);
            $data['messageTimestamp'] = $tmp;
        }

        $message->conversationId = $data['conversationId'];
        if (isset($data['conversationParticipantIds'])) {
            $message->participantIds = json_decode($data['conversationParticipantIds'], true);
        }
        $message->senderId = $data['senderId'];
        $message->senderName = $data['senderName'];
        $message->message = $data['message'];
        $message->messageTimestamp = $data['messageTimestamp'];

        return $message;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'messageId' => (string) $this->messageId,
            'conversationId' => $this->conversationId,
            'conversationParticipantIds' => $this->conversationParticipantIds ? json_encode($this->conversationParticipantIds) : null,
            'senderId' => $this->senderId,
            'senderName' => $this->senderName,
            'message' => $this->message,
            'messageTimestamp' => $this->getMessageTimestamp() ? $this->getMessageTimestamp()->getTimestamp() : null
        ];
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
     * @param string $conversationId
     */
    public function setConversationId(?string $conversationId): void
    {
        $this->conversationId = $conversationId;
    }

    /**
     * @return array
     */
    public function getConversationParticipantIds(): array
    {
        return $this->conversationParticipantIds;
    }

    /**
     * @param array $conversationParticipantIds
     */
    public function setConversationParticipantIds(?array $conversationParticipantIds): void
    {
        $this->conversationParticipantIds = $conversationParticipantIds;
    }

    /**
     * @return string $senderId
     */
    public function getSenderId(string $senderId): string
    {
        return $this->senderId;
    }

    /**
     * @param string $senderId
     */
    public function setSenderId(?string $senderId): void
    {
        $this->senderId = $senderId;
    }

    /**
     * @return string $senderName
     */
    public function getSenderName(string $senderName): string
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName(?string $senderName): void
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string $message
     */
    public function getMessage(string $message): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return \DateTime|null
     */
    public function getMessageTimestamp(): ?\DateTime
    {
        return $this->messageTimestamp;
    }

    /**
     * @param \DateTime $messageTimestamp
     */
    public function setMessageTimestamp(\DateTime $messageTimestamp): void
    {
        $this->messageTimestamp = $messageTimestamp;
    }
}