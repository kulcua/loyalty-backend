<?php

namespace Kulcua\Extension\Component\Warranty\Domain\Command;

use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\Repository\Repository;
use Kulcua\Extension\Component\Warranty\Domain\SystemEvent\WarrantyBookedSystemEvent;
use Kulcua\Extension\Component\Warranty\Domain\SystemEvent\WarrantyUpdatedSystemEvent;
use Kulcua\Extension\Component\Warranty\Domain\SystemEvent\WarrantyDeactivatedSystemEvent;
use Kulcua\Extension\Component\Warranty\Domain\SystemEvent\WarrantySystemEvents;
use Kulcua\Extension\Component\Warranty\Domain\Warranty;

/**
 * Class WarrantyCommandHandler.
 */
class WarrantyCommandHandler extends SimpleCommandHandler
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
     * WarrantyCommandHandler constructor.
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
     * @param BookWarranty $command
     */
    public function handleBookWarranty(BookWarranty $command)
    {
        $warranty = Warranty::createWarranty(
            $command->getWarrantyId(),
            $command->getWarrantyData(),
            $command->getCustomerData()
        );

        $this->repository->save($warranty);

        $this->eventDispatcher->dispatch(
            WarrantySystemEvents::WARRANTY_BOOKED,
            [new WarrantyBookedSystemEvent(
                $command->getWarrantyId(),
                $command->getWarrantyData(),
                $command->getCustomerData()
            )]
        );
    }

    /**
     * @param UpdateWarranty $command
     */
    public function handleUpdateWarranty(UpdateWarranty $command)
    {
        $warrantyId = $command->getWarrantyId();
        $warrantyData = $command->getWarrantyData();
        
        /** @var Warranty $warranty */

        $warranty = $this->repository->load((string) $warrantyId);
        $warranty->updateWarrantyDetails($warrantyData);
        $this->repository->save($warranty);

        $this->eventDispatcher->dispatch(
            WarrantySystemEvents::WARRANTY_UPDATED,
            [new WarrantyUpdatedSystemEvent($command->getWarrantyId())]
        );
    }

    /**
     * @param DeactivateWarranty $command
     */
    public function handleDeactivateWarranty(DeactivateWarranty $command)
    {
        $warrantyId = $command->getWarrantyId();

        /** @var Warranty $warranty */
        $warranty = $this->repository->load((string) $warrantyId);
        $warranty->deactivate();
        // $warranty->setActive($command->isActive());
        $this->repository->save($warranty);

        $this->eventDispatcher->dispatch(
            WarrantySystemEvents::WARRANTY_DEACTIVATED,
            [new WarrantyDeactivatedSystemEvent($warrantyId)]
        );
    }
}
