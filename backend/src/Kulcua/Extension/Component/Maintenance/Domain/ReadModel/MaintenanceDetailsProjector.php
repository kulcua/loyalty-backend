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

    /**
     * @param MaintenanceWasUpdated $event
     */
    protected function applyMaintenanceWasUpdated(MaintenanceWasUpdated $event): void
    {
        $readModel = $this->getReadModel($event->getMaintenanceId());
        $data = $event->getMaintenanceData();

        if (isset($data['productSku'])) {
            $readModel->setProductSku($data['productSku']);
        }

        if (isset($data['bookingDate'])) {
            $readModel->setBookingDate($data['bookingDate']);
        }

        if (isset($data['warrantyCenter'])) {
            $readModel->setWarrantyCenter($data['warrantyCenter']);
        }

        if (isset($data['createdAt'])) {
            $readModel->setcCreatedAt($data['createdAt']);
        }

        if (isset($data['active'])) {
            $readModel->setActive($data['active']);
        }

        $readModel->setCustomerData(CustomerBasicData::deserialize($event->getCustomerData()));

        // if (isset($data['customerId'])) {
        //     if (!$data['customerId'] instanceof CustomerId) {
        //         $data['customerId'] = new CustomerId($data['customerId']);
        //     }
        //     $readModel->setCustomerId($data['customerId']);
        //     if ($readModel->getCustomerId()) {
        //         /** @var Customer $customer */
        //         $customer = $this->customerRepository->byId(
        //             new \Kulcua\Extension\Component\Maintenance\Domain\CustomerId($readModel->getCustomerId()->__toString())
        //         );
        //         if ($customer) {
        //             $readModel->setPosName($customer->getName());
        //             $readModel->setPosCity($customer->getLocation() ? $pos->getLocation()->getCity() : null);
        //         }
        //     }
        // }

        $this->repository->save($readModel);
    }

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
