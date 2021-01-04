<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\ReadModel;

use Broadway\ReadModel\Repository;
use Broadway\Repository\Repository as AggregateRootRepository;
use OpenLoyalty\Component\Core\Infrastructure\Projector\Projector;
// use OpenLoyalty\Component\Pos\Domain\Pos;
// use OpenLoyalty\Component\Pos\Domain\PosId;
// use OpenLoyalty\Component\Pos\Domain\PosRepository;
// use OpenLoyalty\Component\Maintenance\Domain\Event\CustomerWasAssignedToMaintenance;
// use OpenLoyalty\Component\Maintenance\Domain\Event\LabelsWereAppendedToMaintenance;
// use OpenLoyalty\Component\Maintenance\Domain\Event\LabelsWereUpdated;
use Kulcua\Extension\Component\Maintenance\Domain\Event\MaintenanceWasBooked;
use Kulcua\Extension\Component\Maintenance\Domain\Model\CustomerBasicData;
use Kulcua\Extension\Component\Maintenance\Domain\Maintenance;
use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class MaintenanceDetailsProjector.
 */
class MaintenanceDetailsProjector extends Projector
{
    /**
     * @var Repository
     */
    private $repository;

    // /**
    //  * @var PosRepository
    //  */
    // private $posRepository;

    /**
     * @var AggregateRootRepository
     */
    private $maintenanceRepository;

    /**
     * MaintenanceDetailsProjector constructor.
     *
     * @param Repository              $repository
    //  * @param PosRepository           $posRepository
     * @param AggregateRootRepository $maintenanceRepository
     */
    public function __construct(
        Repository $repository,
        // PosRepository $posRepository,
        AggregateRootRepository $maintenanceRepository
    ) {
        $this->repository = $repository;
        // $this->posRepository = $posRepository;
        $this->maintenanceRepository = $maintenanceRepository;
    }

    /**
     * @param MaintenanceWasBooked $event
     */
    protected function applyMaintenanceWasBooked(MaintenanceWasBooked $event): void
    {
        $readModel = $this->getReadModel($event->getMaintenanceId());

        $maintenanceData = $event->getMaintenanceData();
        /** @var Maintenance $maintenance */
        $maintenance = $this->maintenanceRepository->load((string) $event->getMaintenanceId());

        $readModel->setProductSku($maintenanceData['productSku']);
        $readModel->setBookingDate($maintenanceData['bookingDate']);
        $readModel->setWarrantyCenter($maintenanceData['warrantyCenter']);
        $readModel->setCreatedAt($maintenanceData['createdAt']);
        $readModel->setActive($maintenanceData['active']);
        $readModel->setCustomerData(CustomerBasicData::deserialize($event->getCustomerData()));

        // if ($readModel->getPosId()) {
        //     /** @var Pos $pos */
        //     $pos = $this->posRepository->byId(new PosId((string) $readModel->getPosId()));
        //     if ($pos) {
        //         $pos->setMaintenancesAmount($pos->getMaintenancesAmount() + $maintenance->getGrossValue());
        //         $pos->setMaintenancesCount($pos->getMaintenancesCount() + 1);
        //         $this->posRepository->save($pos);
        //     }
        // }

        $this->repository->save($readModel);
    }

    // /**
    //  * @param CustomerWasAssignedToMaintenance $event
    //  */
    // public function applyCustomerWasAssignedToMaintenance(CustomerWasAssignedToMaintenance $event): void
    // {
    //     $readModel = $this->getReadModel($event->getMaintenanceId());
    //     $readModel->setCustomerId($event->getCustomerId());
    //     $customerData = $readModel->getCustomerData();
    //     $customerData->updateEmailAndPhone($event->getEmail(), $event->getPhone());
    //     $this->repository->save($readModel);
    // }

    // /**
    //  * @param LabelsWereAppendedToMaintenance $event
    //  */
    // public function applyLabelsWereAppendedToMaintenance(LabelsWereAppendedToMaintenance $event): void
    // {
    //     $readModel = $this->getReadModel($event->getMaintenanceId());
    //     $readModel->appendLabels($event->getLabels());
    //     $this->repository->save($readModel);
    // }

    // /**
    //  * @param LabelsWereUpdated $event
    //  */
    // public function applyLabelsWereUpdated(LabelsWereUpdated $event): void
    // {
    //     $readModel = $this->getReadModel($event->getMaintenanceId());
    //     $readModel->setLabels($event->getLabels());
    //     $this->repository->save($readModel);
    // }

    /**
     * @param MaintenanceId $maintenanceId
     *
     * @return MaintenanceDetails
     */
    private function getReadModel(MaintenanceId $maintenanceId): MaintenanceDetails
    {
        /** @var MaintenanceDetails $readModel */
        $readModel = $this->repository->find((string) $maintenanceId);

        if (null === $readModel) {
            $readModel = new MaintenanceDetails($maintenanceId);
        }

        return $readModel;
    }
}
