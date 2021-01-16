<?php

namespace Kulcua\Extension\Component\Conversation\Domain\ReadModel;

use Broadway\ReadModel\Repository;
use Broadway\Repository\Repository as AggregateRootRepository;
use OpenLoyalty\Component\Core\Infrastructure\Projector\Projector;
use Kulcua\Extension\Component\Conversation\Domain\Event\ConversationWasCreated;
use Kulcua\Extension\Component\Conversation\Domain\Event\ConversationWasUpdated;
use Kulcua\Extension\Component\Conversation\Domain\Model\CustomerBasicData;
use Kulcua\Extension\Component\Conversation\Domain\Conversation;
use Kulcua\Extension\Component\Conversation\Domain\ConversationId;

/**
 * ClassconversationDetailsProjector.
 */
class ConversationDetailsProjector extends Projector
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var AggregateRootRepository
     */
    private $conversationRepository;

    /**
     * ConversationDetailsProjector constructor.
     *
     * @param Repository              $repository
     * @param AggregateRootRepository $conversationRepository
     */
    public function __construct(
        Repository $repository,
        AggregateRootRepository $conversationRepository
    ) {
        $this->repository = $repository;
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * @param ConversationWasCreated $event
     */
    protected function applyConversationWasCreated(ConversationWasCreated $event): void
    {
        $readModel = $this->getReadModel($event->getConversationId());

        $conversationData = $event->getConversationData();
        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->load((string) $event->getConversationId());

        $readModel->setParticipantIds($conversationData['participantIds']);
        $readModel->setParticipantNames($conversationData['participantNames']);
        $readModel->setLastMessageSnippet($conversationData['lastMessageSnippet']);
        $readModel->setLastMessageTimestamp($conversationData['lastMessageTimestamp']);

        $this->repository->save($readModel);
    }

    /**
     * @param ConversationWasUpdated $event
     */
    protected function applyConversationWasUpdated(ConversationWasUpdated $event): void
    {
        $readModel = $this->getReadModel($event->getConversationId());
        $data = $event->getConversationData();

        if (isset($data['lastMessageSnippet'])) {
            $readModel->setLastMessageSnippet($data['lastMessageSnippet']);
        }

        if (isset($data['lastMessageTimestamp'])) {
            $readModel->setLastMessageTimestamp($data['lastMessageTimestamp']);
        }

        $this->repository->save($readModel);
    }

    /**
     * @param ConversationId $conversationId
     *
     * @return ConversationDetails
     */
    private function getReadModel(ConversationId $conversationId): ConversationDetails
    {
        /** @var ConversationDetails $readModel */
        $readModel = $this->repository->find((string) $conversationId);

        if (null === $readModel) {
            $readModel = new ConversationDetails($conversationId);
        }

        return $readModel;
    }
}
