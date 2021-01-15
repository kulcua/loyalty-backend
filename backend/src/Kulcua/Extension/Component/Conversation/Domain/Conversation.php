<?php

namespace Kulcua\Extension\Component\Conversation\Domain;

use OpenLoyalty\Component\Core\Domain\SnapableEventSourcedAggregateRoot;
use Kulcua\Extension\Component\Conversation\Domain\Event\ConversationWasCreated;
use Kulcua\Extension\Component\Conversation\Domain\Event\ConversationWasUpdated;
use Kulcua\Extension\Component\Conversation\Domain\Model\CustomerBasicData;

/**
 * Class Conversation.
 */
class Conversation extends SnapableEventSourcedAggregateRoot
{
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
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->conversationId;
    }

    /**
     * @param ConversationId $conversationId
     * @param array         $conversationData
     *
     * @return Conversation
     */
    public static function createConversation(
        ConversationId $conversationId,
        array $conversationData
    ): Conversation {
        $conversation = new self();
        $conversation->create(
            $conversationId,
            $conversationData
        );

        return $conversation;
    }

    /**
     * @param ConversationId $conversationId
     * @param array         $conversationData
     * @param array         $customerData
     */
    private function create(
        ConversationId $conversationId,
        array $conversationData
    ): void {
        $this->apply(
            new ConversationWasCreated(
                $conversationId,
                $conversationData
            )
        );
    }

    //In order to find all listeners which are listening for this event, 
    //you have to find all services with tag broadway.domain.event_listener 
    //and with this method
    /**
     * @param ConversationWasCreated $event
     */
    protected function applyConversationWasCreated(ConversationWasCreated $event): void
    {
        $conversationData = $event->getConversationData();
        $this->conversationId = $event->getConversationId();
        $this->participantIds = $conversationData['participantIds'];
        $this->participantNames = $conversationData['participantNames'];
        $this->lastMessageSnippet = $conversationData['lastMessageSnippet'];
        $this->lastMessageTimestamp = $conversationData['lastMessageTimestamp'];
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
     * @return array
     */
    public function getParticipantNames(): array
    {
        return $this->participantNames;
    }

    /**
     * @return string
     */
    public function getLastMessageSnippet(): string
    {
        return $this->lastMessageSnippet;
    }

    /**
     * @return \DateTime
     */
    public function getLastMessageTimestamp(): \DateTime
    {
        return $this->lastMessageTimestamp;
    }
}