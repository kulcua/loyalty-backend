<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\ReadModel;

use Broadway\ReadModel\Repository;
use Broadway\Repository\Repository as AggregateRootRepository;
use OpenLoyalty\Component\Core\Infrastructure\Projector\Projector;
use Kulcua\Extension\Component\SuggestionBox\Domain\Event\SuggestionBoxWasCreated;
use Kulcua\Extension\Component\SuggestionBox\Domain\Event\SuggestionBoxWasUpdated;
use Kulcua\Extension\Component\SuggestionBox\Domain\Model\CustomerBasicData;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBox;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Kulcua\Extension\Component\SuggestionBox\Domain\Event\SuggestionBoxWasDeactivated;

/**
 * ClasssuggestionBoxDetailsProjector.
 */
class SuggestionBoxDetailsProjector extends Projector
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var AggregateRootRepository
     */
    private $suggestionBoxRepository;

    /**
     * SuggestionBoxDetailsProjector constructor.
     *
     * @param Repository              $repository
     * @param AggregateRootRepository $suggestionBoxRepository
     */
    public function __construct(
        Repository $repository,
        AggregateRootRepository $suggestionBoxRepository
    ) {
        $this->repository = $repository;
        $this->suggestionBoxRepository = $suggestionBoxRepository;
    }

    /**
     * @param SuggestionBoxWasCreated $event
     */
    protected function applySuggestionBoxWasCreated(SuggestionBoxWasCreated $event): void
    {
        $readModel = $this->getReadModel($event->getSuggestionBoxId());

        $suggestionBoxData = $event->getSuggestionBoxData();
        /** @var SuggestionBox $suggestion_box */
        $suggestion_box = $this->suggestionBoxRepository->load((string) $event->getSuggestionBoxId());

        $readModel->setSenderId($suggestionBoxData['senderId']);
        $readModel->setSenderName($suggestionBoxData['senderName']);
        $readModel->setProblemType($suggestionBoxData['problemType']);
        $readModel->setDescription($suggestionBoxData['description']);
        $readModel->setActive($suggestionBoxData['active']);
        $readModel->setTimestamp($suggestionBoxData['timestamp']);

        $this->repository->save($readModel);
    }

    /**
     * @param SuggestionBoxId $suggestionBoxId
     *
     * @return SuggestionBoxDetails
     */
    private function getReadModel(SuggestionBoxId $suggestionBoxId): SuggestionBoxDetails
    {
        /** @var SuggestionBoxDetails $readModel */
        $readModel = $this->repository->find((string) $suggestionBoxId);

        if (null === $readModel) {
            $readModel = new SuggestionBoxDetails($suggestionBoxId);
        }

        return $readModel;
    }

    /**
     * @param SuggestionBoxWasUpdated $event
     */
    protected function applySuggestionBoxWasUpdated(SuggestionBoxWasUpdated $event): void
    {
        $readModel = $this->getReadModel($event->getSuggestionBoxId());
        $data = $event->getSuggestionBoxData();

        if (isset($data['senderId'])) {
            $readModel->setSenderId($data['senderId']);
        }

        if (isset($data['senderName'])) {
            $readModel->setSenderName($data['senderName']);
        }

        if (isset($data['problemType'])) {
            $readModel->setProblemType($data['problemType']);
        }

        if (isset($data['description'])) {
            $readModel->setDescription($data['description']);
        }

        if (isset($data['timestamp'])) {
            $readModel->setTimestamp($data['timestamp']);
        }

        if (isset($data['active'])) {
            $readModel->setActive($data['active']);
        }

        $this->repository->save($readModel);
    }

    /**
     * @param SuggestionBoxWasDeactivated $event
     */
    protected function applySuggestionBoxWasDeactivated(SuggestionBoxWasDeactivated $event): void
    {
        /** @var SuggestionBoxDetails $readModel */
        $readModel = $this->getReadModel($event->getSuggestionBoxId());
        $readModel->setActive(false);
        $this->repository->save($readModel);
    }
}
