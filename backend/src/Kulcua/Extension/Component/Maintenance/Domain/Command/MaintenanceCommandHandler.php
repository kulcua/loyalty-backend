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
            $command->getTransactionData(),
            $command->getCustomerData(),
            $command->getItems(),
            $command->getPosId(),
            $command->getExcludedDeliverySKUs(),
            $command->getExcludedLevelSKUs(),
            $command->getExcludedCategories(),
            $command->getRevisedDocument(),
            $command->getLabels()
        );

        $this->repository->save($maintenance);

        $this->eventDispatcher->dispatch(
            MaintenanceSystemEvents::MAINTENANCE_BOOKED,
            [new MaintenanceBookedEvent(
                $command->getMaintenanceId(),
                $command->getTransactionData(),
                $command->getCustomerData(),
                $command->getItems(),
                $command->getPosId()
            )]
        );
    }

    // /**
    //  * @param AppendLabelsToTransaction $command
    //  */
    // public function handleAppendLabelsToTransaction(AppendLabelsToTransaction $command)
    // {
    //     /** @var Transaction $transaction */
    //     $transaction = $this->repository->load($command->getTransactionId()->__toString());
    //     $transaction->appendLabels($command->getLabels());
    //     $this->repository->save($transaction);
    // }

    // /**
    //  * @param EditTransactionLabels $command
    //  */
    // public function handleEditTransactionLabels(EditTransactionLabels $command)
    // {
    //     /** @var Transaction $transaction */
    //     $transaction = $this->repository->load($command->getTransactionId()->__toString());
    //     $transaction->setLabels($command->getLabels());
    //     $this->repository->save($transaction);
    // }

    // /**
    //  * @param AssignCustomerToTransaction $command
    //  */
    // public function handleAssignCustomerToTransaction(AssignCustomerToTransaction $command)
    // {
    //     /** @var Transaction $transaction */
    //     $transaction = $this->repository->load((string) $command->getTransactionId());
    //     $transaction->assignCustomerToTransaction(
    //         $command->getCustomerId(),
    //         $command->getEmail(),
    //         $command->getPhone()
    //     );
    //     $this->repository->save($transaction);
    // }
}
