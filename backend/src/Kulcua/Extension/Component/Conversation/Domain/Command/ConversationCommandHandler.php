<?php

namespace Kulcua\Extension\Component\Conversation\Domain\Command;

use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\Repository\Repository;
use Kulcua\Extension\Component\Conversation\Domain\SystemEvent\ConversationCreatedSystemEvent;
use Kulcua\Extension\Component\Conversation\Domain\SystemEvent\ConversationSystemEvents;
use Kulcua\Extension\Component\Conversation\Domain\Conversation;

/**
 * Class ConversationCommandHandler.
 */
class ConversationCommandHandler extends SimpleCommandHandler
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
     * ConversationCommandHandler constructor.
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
     * @param CreateConversation $command
     */
    public function handleCreateConversation(CreateConversation $command)
    {
        $conversation = Conversation::createConversation(
            $command->getConversationId(),
            $command->getConversationData()
        );

        $this->repository->save($conversation);

        $this->eventDispatcher->dispatch(
            ConversationSystemEvents::CONVERSATION_CREATED,
            [new ConversationCreatedSystemEvent(
                $command->getConversationId(),
                $command->getConversationData()
            )]
        );
    }
}
