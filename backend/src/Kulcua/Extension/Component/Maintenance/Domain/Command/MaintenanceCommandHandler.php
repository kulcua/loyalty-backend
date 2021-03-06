<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Command;

use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\Repository\Repository;
use Kulcua\Extension\Component\Maintenance\Domain\SystemEvent\MaintenanceBookedSystemEvent;
use Kulcua\Extension\Component\Maintenance\Domain\SystemEvent\MaintenanceUpdatedSystemEvent;
use Kulcua\Extension\Component\Maintenance\Domain\SystemEvent\MaintenanceDeactivatedSystemEvent;
use Kulcua\Extension\Component\Maintenance\Domain\SystemEvent\MaintenanceSystemEvents;
use Kulcua\Extension\Component\Maintenance\Domain\Maintenance;

/**
 * Class MaintenanceCommandHandler.
 */
class MaintenanceCommandHandler extends SimpleCommandHandler
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
     * MaintenanceCommandHandler constructor.
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
     * @param BookMaintenance $command
     */
    public function handleBookMaintenance(BookMaintenance $command)
    {
        $maintenance = Maintenance::createMaintenance(
            $command->getMaintenanceId(),
            $command->getMaintenanceData(),
            $command->getCustomerData()
        );

        $this->repository->save($maintenance);

        $this->eventDispatcher->dispatch(
            MaintenanceSystemEvents::MAINTENANCE_BOOKED,
            [new MaintenanceBookedSystemEvent(
                $command->getMaintenanceId(),
                $command->getMaintenanceData(),
                $command->getCustomerData()
            )]
        );
    }

    /**
     * @param UpdateMaintenance $command
     */
    public function handleUpdateMaintenance(UpdateMaintenance $command)
    {
        $maintenanceId = $command->getMaintenanceId();
        $maintenanceData = $command->getMaintenanceData();
        
        /** @var Maintenance $maintenance */

        $maintenance = $this->repository->load((string) $maintenanceId);
        $maintenance->updateMaintenanceDetails($maintenanceData);
        $this->repository->save($maintenance);

        $this->eventDispatcher->dispatch(
            MaintenanceSystemEvents::MAINTENANCE_UPDATED,
            [new MaintenanceUpdatedSystemEvent($command->getMaintenanceId())]
        );
    }

    /**
     * @param DeactivateMaintenance $command
     */
    public function handleDeactivateMaintenance(DeactivateMaintenance $command)
    {
        $maintenanceId = $command->getMaintenanceId();

        /** @var Maintenance $maintenance */
        $maintenance = $this->repository->load((string) $maintenanceId);
        $maintenance->deactivate();
        // $maintenance->setActive($command->isActive());
        $this->repository->save($maintenance);

        $this->eventDispatcher->dispatch(
            MaintenanceSystemEvents::MAINTENANCE_DEACTIVATED,
            [new MaintenanceDeactivatedSystemEvent($maintenanceId)]
        );
    }
}
