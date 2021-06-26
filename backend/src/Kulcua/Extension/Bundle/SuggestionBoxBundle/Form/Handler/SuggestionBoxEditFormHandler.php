<?php

namespace Kulcua\Extension\Bundle\SuggestionBoxBundle\Form\Handler;

use Broadway\CommandHandling\CommandBus;
use Doctrine\ORM\EntityManager;
use Kulcua\Extension\Component\SuggestionBox\Domain\Command\UpdateSuggestionBox;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Kulcua\Extension\Component\SuggestionBox\Domain\ReadModel\SuggestionBoxDetailsRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
/**
 * Class SuggestionBoxEditFormHandler.
 */
class SuggestionBoxEditFormHandler
{
    /**
     * @var SuggestionBoxDetailsRepository
     */
    protected $suggestionBoxIdDetailsRepository;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * SuggestionBoxEditFormHandler constructor.
     *
     * @param CommandBus    $commandBus
     */
    public function __construct( CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function onSuccess(SuggestionBoxId $suggestionBoxId, FormInterface $form)
    {
        $suggestionBoxIdData = $form->getData();

        $command = new UpdateSuggestionBox($suggestionBoxId, $suggestionBoxIdData);

        $this->commandBus->dispatch($command);

        return true;;
    }
}
