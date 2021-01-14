<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Form\Handler;

use Broadway\CommandHandling\CommandBus;
use Doctrine\ORM\EntityManager;
use Kulcua\Extension\Component\Maintenance\Domain\Command\UpdateMaintenance;
use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;
use Kulcua\Extension\Component\Maintenance\Domain\ReadModel\MaintenanceDetailsRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
/**
 * Class MaintenanceEditFormHandler.
 */
class MaintenanceEditFormHandler
{
    /**
     * @var MaintenanceDetailsRepository
     */
    protected $maintenanceDetailsRepository;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * MaintenanceEditFormHandler constructor.
     *
     * @param CommandBus    $commandBus
     */
    public function __construct( CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function onSuccess(MaintenanceId $maintenanceId, FormInterface $form)
    {
        $maintenanceData = $form->getData();

        $command = new UpdateMaintenance($maintenanceId, $maintenanceData);

        $this->commandBus->dispatch($command);

        return true;;
    }
}
