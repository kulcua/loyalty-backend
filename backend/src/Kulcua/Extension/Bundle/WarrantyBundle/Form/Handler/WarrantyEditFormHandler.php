<?php

namespace Kulcua\Extension\Bundle\WarrantyBundle\Form\Handler;

use Broadway\CommandHandling\CommandBus;
use Doctrine\ORM\EntityManager;
use Kulcua\Extension\Component\Warranty\Domain\Command\UpdateWarranty;
use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;
use Kulcua\Extension\Component\Warranty\Domain\ReadModel\WarrantyDetailsRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
/**
 * Class WarrantyEditFormHandler.
 */
class WarrantyEditFormHandler
{
    /**
     * @var WarrantyDetailsRepository
     */
    protected $warrantyDetailsRepository;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * WarrantyEditFormHandler constructor.
     *
     * @param CommandBus    $commandBus
     */
    public function __construct( CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function onSuccess(WarrantyId $warrantyId, FormInterface $form)
    {
        $warrantyData = $form->getData();

        $command = new UpdateWarranty($warrantyId, $warrantyData);

        $this->commandBus->dispatch($command);

        return true;;
    }
}
