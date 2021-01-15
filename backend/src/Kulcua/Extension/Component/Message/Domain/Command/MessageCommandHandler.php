<?php

namespace Kulcua\Extension\Component\Message\Domain\Command;

use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\Repository\Repository;
use Kulcua\Extension\Component\Message\Domain\SystemEvent\MessageCreatedSystemEvent;
use Kulcua\Extension\Component\Message\Domain\SystemEvent\MessageSystemEvents;
use Kulcua\Extension\Component\Message\Domain\Message;

/**
 * Class MessageCommandHandler.
 */
class MessageCommandHandler extends SimpleCommandHandler
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
     * MessageCommandHandler constructor.
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
     * @param CreateMessage $command
     */
    public function handleCreateMessage(CreateMessage $command)
    {
        $message = Message::createMessage(
            $command->getMessageId(),
            $command->getMessageData()
        );

        $this->repository->save($message);

        $this->eventDispatcher->dispatch(
            MessageSystemEvents::MESSAGE_CREATED,
            [new MessageCreatedSystemEvent(
                $command->getMessageId(),
                $command->getMessageData()
            )]
        );
    }
}
