<?php

namespace Kulcua\Extension\Component\Maintenance\Domain;

use OpenLoyalty\Component\Core\Domain\SnapableEventSourcedAggregateRoot;
use Kulcua\Extension\Component\Maintenance\Domain\Event\MaintenanceWasBooked;
use Kulcua\Extension\Component\Maintenance\Domain\Event\MaintenanceWasUpdated;
use Kulcua\Extension\Component\Maintenance\Domain\Model\CustomerBasicData;

/**
 * Class Maintenance.
 */
class Maintenance extends SnapableEventSourcedAggregateRoot
{
    /**
     * @var MaintenanceId
     */
    protected $maintenanceId;

    /**
     * @var CustomerId|null
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $productSku;

    /**
     * @var \DateTime
     */
    protected $bookingDate;

     /**
     * @var string
     */
    protected $bookingTime;

    /**
     * @var string
     */
    protected $warrantyCenter;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var CustomerBasicData
     */
    protected $customerData;

    /**
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->maintenanceId;
    }

    /**
     * @param MaintenanceId $maintenanceId
     * @param array         $maintenanceData
     * @param array         $customerData
     *
     * @return Maintenance
     */
    public static function createMaintenance(
        MaintenanceId $maintenanceId,
        array $maintenanceData,
        array $customerData
    ): Maintenance {
        $maintenance = new self();
        $maintenance->create(
            $maintenanceId,
            $maintenanceData,
            $customerData
        );

        return $maintenance;
    }

    /**
     * @param MaintenanceId $maintenanceId
     * @param array         $maintenanceData
     * @param array         $customerData
     */
    private function create(
        MaintenanceId $maintenanceId,
        array $maintenanceData,
        array $customerData
    ): void {
        $this->apply(
            new MaintenanceWasBooked(
                $maintenanceId,
                $maintenanceData,
                $customerData
            )
        );
    }

    //In order to find all listeners which are listening for this event, 
    //you have to find all services with tag broadway.domain.event_listener 
    //and with this method
    /**
     * @param MaintenanceWasBooked $event
     */
    protected function applyMaintenanceWasBooked(MaintenanceWasBooked $event): void
    {
        $maintenanceData = $event->getMaintenanceData();
        $this->maintenanceId = $event->getMaintenanceId();
        $this->productSku = $maintenanceData['productSku'];
        $this->bookingDate = $maintenanceData['bookingDate'];
        $this->bookingTime = $maintenanceData['bookingTime'];
        $this->warrantyCenter = $maintenanceData['warrantyCenter'];
        $this->createdAt = $maintenanceData['createdAt'];
        $this->customerData = CustomerBasicData::deserialize($event->getCustomerData());
    }

    /**
     * @param array $customerData
     */
    public function updateMaintenanceDetails(array $maintenanceData): void
    {
        $this->apply(
            new MaintenanceWasUpdated($this->getMaintenanceId(), $maintenanceData)
        );
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->getMaintenanceId();
    }

    /**
     * @return MaintenanceId
     */
    public function getMaintenanceId(): MaintenanceId
    {
        return $this->maintenanceId;
    }

    /**
     * @return CustomerId|null
     */
    public function getCustomerId(): ?CustomerId
    {
        return $this->customerId;
    }

    /**
     * @return string
     */
    public function getProductSku(): string
    {
        return $this->productSku;
    }

    /**
     * @return string
     */
    public function getWarrantyCenter(): string
    {
        return $this->warrantyCenter;
    }

    /**
     * @return string
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @return \DateTime
     */
    public function getBookingDate(): \DateTime
    {
        return $this->bookingDate;
    }

    /**
     * @return string
     */
    public function getBookingTime(): bool
    {
        return $this->bookingTime;
    }

    /**
     * @return CustomerBasicData
     */
    public function getCustomerData(): CustomerBasicData
    {
        return $this->customerData;
    }
}