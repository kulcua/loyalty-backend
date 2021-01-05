<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Command;

use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\Repository\Repository;
use Kulcua\Extension\Component\Maintenance\Domain\SystemEvent\MaintenanceBookedEvent;
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
            [new MaintenanceBookedEvent(
                $command->getMaintenanceId(),
                $command->getMaintenanceData(),
                $command->getCustomerData()
            )]
        );
    }

    // /**
    //  * @param AppendLabelsToMaintenance $command
    //  */
    // public function handleAppendLabelsToMaintenance(AppendLabelsToMaintenance $command)
    // {
    //     /** @var Maintenance $maintenance */
    //     $maintenance = $this->repository->load($command->getMaintenanceId()->__toString());
    //     $maintenance->appendLabels($command->getLabels());
    //     $this->repository->save($maintenance);
    // }

    // /**
    //  * @param EditMaintenanceLabels $command
    //  */
    // public function handleEditMaintenanceLabels(EditMaintenanceLabels $command)
    // {
    //     /** @var Maintenance $maintenance */
    //     $maintenance = $this->repository->load($command->getMaintenanceId()->__toString());
    //     $maintenance->setLabels($command->getLabels());
    //     $this->repository->save($maintenance);
    // }

    // /**
    //  * @param AssignCustomerToMaintenance $command
    //  */
    // public function handleAssignCustomerToMaintenance(AssignCustomerToMaintenance $command)
    // {
    //     /** @var Maintenance $maintenance */
    //     $maintenance = $this->repository->load((string) $command->getMaintenanceId());
    //     $maintenance->assignCustomerToMaintenance(
    //         $command->getCustomerId(),
    //         $command->getEmail(),
    //         $command->getPhone()
    //     );
    //     $this->repository->save($maintenance);
    // }
}
