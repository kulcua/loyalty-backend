<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Command;

use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\Repository\Repository;
use Kulcua\Extension\Component\SuggestionBox\Domain\SystemEvent\SuggestionBoxCreatedSystemEvent;
use Kulcua\Extension\Component\SuggestionBox\Domain\SystemEvent\SuggestionBoxSystemEvents;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBox;
use Kulcua\Extension\Component\SuggestionBox\Domain\SystemEvent\SuggestionBoxUpdatedSystemEvent;
use Kulcua\Extension\Component\SuggestionBox\Domain\SystemEvent\SuggestionBoxDeactivatedSystemEvent;

/**
 * Class SuggestionBoxCommandHandler.
 */
class SuggestionBoxCommandHandler extends SimpleCommandHandler
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * SuggestionBoxCommandHandler constructor.
     *
     * @param Repository      $repository
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(Repository $repository, EventDispatcher $eventDispatcher)
    {
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    //ALL COMMAND PUT  HERE

    /**
     * @param CreateSuggestionBox $command
     */
    public function handleCreateSuggestionBox(CreateSuggestionBox $command)
    {
        $suggestionBox = SuggestionBox::createSuggestionBox(
            $command->getSuggestionBoxId(),
            $command->getSuggestionBoxData()
        );

        $this->repository->save($suggestionBox);

        $this->eventDispatcher->dispatch(
            SuggestionBoxSystemEvents::SUGGESTION_BOX_CREATED,
            [new SuggestionBoxCreatedSystemEvent(
                $command->getSuggestionBoxId(),
                $command->getSuggestionBoxData()
            )]
        );
    }

    /**
     * @param UpdateSuggestionBox $command
     */
    public function handleUpdateSuggestionBox(UpdateSuggestionBox $command)
    {
        $suggestionBoxId = $command->getSuggestionBoxId();
        $suggestionBoxData = $command->getSuggestionBoxData();
        
        /** @var SuggestionBox $suggestionBox */

        $suggestionBox = $this->repository->load((string) $suggestionBoxId);
        $suggestionBox->updateSuggestionBoxDetails($suggestionBoxData);
        $this->repository->save($suggestionBox);

        $this->eventDispatcher->dispatch(
            SuggestionBoxSystemEvents::SUGGESTION_BOX_UPDATED,
            [new SuggestionBoxUpdatedSystemEvent($command->getSuggestionBoxId())]
        );
    }

    /**
     * @param DeactivateSuggestionBox $command
     */
    public function handleDeactivateSuggestionBox(DeactivateSuggestionBox $command)
    {
        $suggestionBoxId = $command->getSuggestionBoxId();

        /** @var SuggestionBox $suggestionBox */
        $suggestionBox = $this->repository->load((string) $suggestionBoxId);
        $suggestionBox->deactivate();
        // $suggestionBox->setActive($command->isActive());
        $this->repository->save($suggestionBox);

        $this->eventDispatcher->dispatch(
            SuggestionBoxSystemEvents::SUGGESTION_BOX_DEACTIVATED,
            [new SuggestionBoxDeactivatedSystemEvent($suggestionBoxId)]
        );
    }
}
