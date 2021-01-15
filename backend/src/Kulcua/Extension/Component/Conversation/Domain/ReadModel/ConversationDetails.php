<?php

namespace Kulcua\Extension\Component\Conversation\Domain\ReadModel;

use Broadway\ReadModel\SerializableReadModel;
use OpenLoyalty\Component\Core\Domain\ReadModel\Versionable;
use OpenLoyalty\Component\Core\Domain\ReadModel\VersionableReadModel;
use Kulcua\Extension\Component\Conversation\Domain\CustomerId;
use Kulcua\Extension\Component\Conversation\Domain\Model\CustomerBasicData;
use Kulcua\Extension\Component\Conversation\Domain\Conversation;
use Kulcua\Extension\Component\Conversation\Domain\ConversationId;

/**
 * Class ConversationDetails.
 */
class ConversationDetails implements SerializableReadModel, VersionableReadModel
{
    use Versionable;

    /**
     * @var ConversationId
     */
    protected $conversationId;

    /**
     * @var array
     */
    protected $participantIds;

    /**
     * @var array
     */
    protected $participantNames;

    /**
     * @var string
     */
    protected $lastMessageSnippet;

    /**
     * @var \DateTime
     */
    protected $lastMessageTimestamp;

    /**
     * ConversationDetails constructor.
     *
     * @param ConversationId $conversationId
     */
    public function __construct(ConversationId $conversationId)
    {
        $this->conversationId = $conversationId;
    }


    /**
     * @param array $data
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    { 
        $conversation = new self(new ConversationId($data['conversationId']));

        if (is_numeric($data['lastMessageTimestamp'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($data['lastMessageTimestamp']);
            $data['lastMessageTimestamp'] = $tmp;
        }

        if (isset($data['participantIds'])) {
            $conversation->participantIds = json_decode($data['participantIds'], true);
        }
        if (isset($data['participantNames'])) {
            $conversation->participantNames = json_decode($data['participantNames'], true);
        }
        $conversation->lastMessageSnippet = $data['lastMessageSnippet'];
        $conversation->lastMessageTimestamp = $data['lastMessageTimestamp'];

        return $conversation;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'conversationId' => (string) $this->conversationId,
            'participantIds' => $this->participantIds ? json_encode($this->participantIds) : null,
            'participantNames' => $this->participantNames ? json_encode($this->participantNames) : null,           
            'lastMessageSnippet' => $this->lastMessageSnippet,
            'lastMessageTimestamp' => $this->getLastMessageTimestamp() ? $this->getLastMessageTimestamp()->getTimestamp() : null
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->getConversationId();
    }

    /**
     * @return ConversationId
     */
    public function getConversationId(): ConversationId
    {
        return $this->conversationId;
    }

    /**
     * @return array
     */
    public function getParticipantIds(): array
    {
        return $this->participantIds;
    }

    /**
     * @param array $participantIds
     */
    public function setParticipantIds(?array $participantIds): void
    {
        $this->participantIds = $participantIds;
    }

    /**
     * @return array
     */
    public function getParticipantNames(): array
    {
        return $this->participantNames;
    }

    /**
     * @param array $participantNames
     */
    public function setParticipantNames(?array $participantNames): void
    {
        $this->participantNames = $participantNames;
    }

    /**
     * @return string $lastMessageSnippet
     */
    public function getLastMessageSnippet(string $lastMessageSnippet): string
    {
        return $this->lastMessageSnippet;
    }

    /**
     * @param string $lastMessageSnippet
     */
    public function setLastMessageSnippet(?string $lastMessageSnippet): void
    {
        $this->lastMessageSnippet = $lastMessageSnippet;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastMessageTimestamp(): ?\DateTime
    {
        return $this->lastMessageTimestamp;
    }

    /**
     * @param \DateTime $lastMessageTimestamp
     */
    public function setLastMessageTimestamp(\DateTime $lastMessageTimestamp): void
    {
        $this->lastMessageTimestamp = $lastMessageTimestamp;
    }
}