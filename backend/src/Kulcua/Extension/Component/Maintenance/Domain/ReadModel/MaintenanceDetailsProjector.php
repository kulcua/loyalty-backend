<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\ReadModel;

use Broadway\ReadModel\Repository;
use Broadway\Repository\Repository as AggregateRootRepository;
use OpenLoyalty\Component\Core\Infrastructure\Projector\Projector;
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

    /**
     * @var AggregateRootRepository
     */
    private $maintenanceRepository;

    /**
     * MaintenanceDetailsProjector constructor.
     *
     * @param Repository              $repository
     * @param AggregateRootRepository $maintenanceRepository
     */
    public function __construct(
        Repository $repository,
        AggregateRootRepository $maintenanceRepository
    ) {
        $this->repository = $repository;
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
