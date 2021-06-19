<?php

namespace Kulcua\Extension\Component\Warranty\Domain\ReadModel;

use Broadway\ReadModel\Repository;
use Broadway\Repository\Repository as AggregateRootRepository;
use OpenLoyalty\Component\Core\Infrastructure\Projector\Projector;
use Kulcua\Extension\Component\Warranty\Domain\Event\WarrantyWasBooked;
use Kulcua\Extension\Component\Warranty\Domain\Event\WarrantyWasUpdated;
use Kulcua\Extension\Component\Warranty\Domain\Event\WarrantyWasDeactivated;
use Kulcua\Extension\Component\Warranty\Domain\Model\CustomerBasicData;
use Kulcua\Extension\Component\Warranty\Domain\Warranty;
use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;

/**
 * Class WarrantyDetailsProjector.
 */
class WarrantyDetailsProjector extends Projector
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var AggregateRootRepository
     */
    private $warrantyRepository;

    /**
     * WarrantyDetailsProjector constructor.
     *
     * @param Repository              $repository
     * @param AggregateRootRepository $warrantyRepository
     */
    public function __construct(
        Repository $repository,
        AggregateRootRepository $warrantyRepository
    ) {
        $this->repository = $repository;
        $this->warrantyRepository = $warrantyRepository;
    }

    /**
     * @param WarrantyWasBooked $event
     */
    protected function applyWarrantyWasBooked(WarrantyWasBooked $event): void
    {
        $readModel = $this->getReadModel($event->getWarrantyId());

        $warrantyData = $event->getWarrantyData();
        /** @var Warranty $warranty */
        $warranty = $this->warrantyRepository->load((string) $event->getWarrantyId());

        $readModel->setProductSku($warrantyData['productSku']);
        $readModel->setBookingDate($warrantyData['bookingDate']);
        $readModel->setBookingTime($warrantyData['bookingTime']);
        $readModel->setWarrantyCenter($warrantyData['warrantyCenter']);
        $readModel->setCreatedAt($warrantyData['createdAt']);
        $readModel->setActive($warrantyData['active']);
        $readModel->setCustomerData(CustomerBasicData::deserialize($event->getCustomerData()));

        $this->repository->save($readModel);
    }

    /**
     * @param WarrantyWasUpdated $event
     */
    protected function applyWarrantyWasUpdated(WarrantyWasUpdated $event): void
    {
        $readModel = $this->getReadModel($event->getWarrantyId());
        $data = $event->getWarrantyData();

        if (isset($data['productSku'])) {
            $readModel->setProductSku($data['productSku']);
        }

        if (isset($data['bookingDate'])) {
            $readModel->setBookingDate($data['bookingDate']);
        }

        if (isset($data['bookingTime'])) {
            $readModel->setBookingTime($data['bookingTime']);
        }

        if (isset($data['warrantyCenter'])) {
            $readModel->setWarrantyCenter($data['warrantyCenter']);
        }

        if (isset($data['createdAt'])) {
            $readModel->setCreatedAt($data['createdAt']);
        }

        if (isset($data['active'])) {
            $readModel->setActive($data['active']);
        }

        $this->repository->save($readModel);
    }

    /**
     * @param WarrantyWasDeactivated $event
     */
    protected function applyWarrantyWasDeactivated(WarrantyWasDeactivated $event): void
    {
        /** @var WarrantyDetails $readModel */
        $readModel = $this->getReadModel($event->getWarrantyId());
        $readModel->setActive(false);
        $this->repository->save($readModel);
    }

    /**
     * @param WarrantyId $warrantyId
     *
     * @return WarrantyDetails
     */
    private function getReadModel(WarrantyId $warrantyId): WarrantyDetails
    {
        /** @var WarrantyDetails $readModel */
        $readModel = $this->repository->find((string) $warrantyId);

        if (null === $readModel) {
            $readModel = new WarrantyDetails($warrantyId);
        }

        return $readModel;
    }
}
