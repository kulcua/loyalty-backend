<?php

namespace Kulcua\Extension\Component\Message\Domain\ReadModel;

use Broadway\ReadModel\Repository;
use Broadway\Repository\Repository as AggregateRootRepository;
use OpenLoyalty\Component\Core\Infrastructure\Projector\Projector;
use Kulcua\Extension\Component\Message\Domain\Event\MessageWasCreated;
use Kulcua\Extension\Component\Message\Domain\Event\MessageWasUpdated;
use Kulcua\Extension\Component\Message\Domain\Model\CustomerBasicData;
use Kulcua\Extension\Component\Message\Domain\Message;
use Kulcua\Extension\Component\Message\Domain\MessageId;

/**
 * ClassmessageDetailsProjector.
 */
class MessageDetailsProjector extends Projector
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var AggregateRootRepository
     */
    private $messageRepository;

    /**
     * MessageDetailsProjector constructor.
     *
     * @param Repository              $repository
     * @param AggregateRootRepository $messageRepository
     */
    public function __construct(
        Repository $repository,
        AggregateRootRepository $messageRepository
    ) {
        $this->repository = $repository;
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param MessageWasCreated $event
     */
    protected function applyMessageWasCreated(MessageWasCreated $event): void
    {
        $readModel = $this->getReadModel($event->getMessageId());

        $messageData = $event->getMessageData();
        /** @var Message $message */
        $message = $this->messageRepository->load((string) $event->getMessageId());

        $readModel->setConversationId($messageData['conversationId']);
        $readModel->setConversationParticipantIds($messageData['conversationParticipantIds']);
        $readModel->setSenderId($messageData['senderId']);
        $readModel->setSenderName($messageData['senderName']);
        $readModel->setMessage($messageData['message']);
        $readModel->setMessageTimestamp($messageData['messageTimestamp']);

        $this->repository->save($readModel);
    }

    /**
     * @param MessageId $messageId
     *
     * @return MessageDetails
     */
    private function getReadModel(MessageId $messageId): MessageDetails
    {
        /** @var MessageDetails $readModel */
        $readModel = $this->repository->find((string) $messageId);

        if (null === $readModel) {
            $readModel = new MessageDetails($messageId);
        }

        return $readModel;
    }
}
