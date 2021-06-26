<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\Command;

use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\Repository\Repository;
use Kulcua\Extension\Component\SuggestionBox\Domain\SystemEvent\SuggestionBoxCreatedSystemEvent;
use Kulcua\Extension\Component\SuggestionBox\Domain\SystemEvent\SuggestionBoxSystemEvents;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBox;

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
     * @param CreatePhotoSuggestionBox $command
     */
    public function handleCreatePhotoSuggestionBox(CreatePhotoSuggestionBox $command)
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
}
