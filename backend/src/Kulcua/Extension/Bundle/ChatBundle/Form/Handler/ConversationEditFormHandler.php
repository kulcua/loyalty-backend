<?php

namespace Kulcua\Extension\Bundle\ChatBundle\Form\Handler;

use Broadway\CommandHandling\CommandBus;
use Doctrine\ORM\EntityManager;
use Kulcua\Extension\Component\Conversation\Domain\Command\UpdateConversation;
use Kulcua\Extension\Component\Conversation\Domain\ConversationId;
use Kulcua\Extension\Component\Conversation\Domain\ReadModel\ConversationDetailsRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
/**
 * Class ConversationEditFormHandler.
 */
class ConversationEditFormHandler
{
    /**
     * @var ConversationDetailsRepository
     */
    protected $conversationDetailsRepository;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * ConversationEditFormHandler constructor.
     *
     * @param CommandBus    $commandBus
     */
    public function __construct( CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function onSuccess(ConversationId $conversationId, FormInterface $form)
    {
        $conversationData = $form->getData();

        $command = new UpdateConversation($conversationId, $conversationData);

        $this->commandBus->dispatch($command);

        return true;;
    }
}
