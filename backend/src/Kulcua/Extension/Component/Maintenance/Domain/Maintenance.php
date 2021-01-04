<?php

namespace Kulcua\Extension\Component\Maintenance\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Label;
use OpenLoyalty\Component\Core\Domain\Model\SKU;
use OpenLoyalty\Component\Core\Domain\SnapableEventSourcedAggregateRoot;
use Kulcua\Extension\Component\Maintenance\Domain\Event\MaintenanceWasBooked;

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
     * @var CustomerId
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
        array $customerData,
    ): Maintenance {
        $maintenance = new self();
        $maintenance->create(
            $maintenanceId,
            $maintenanceData,
            $customerData,
        );

        return $maintenance;
    }

    
    // /**
    //  * @param CustomerId  $customerId
    //  * @param string|null $email
    //  * @param string|null $phone
    //  */
    // public function assignCustomerToMaintenance(
    //     CustomerId $customerId,
    //     string $email = null,
    //     string $phone = null
    // ): void {
    //     $this->apply(
    //         new CustomerWasAssignedToMaintenance($this->maintenanceId, $customerId, $email, $phone)
    //     );
    // }

    // /**
    //  * @param array $labels
    //  */
    // public function appendLabels(array $labels = []): void
    // {
    //     $this->apply(
    //         new LabelsWereAppendedToMaintenance($this->maintenanceId, $labels)
    //     );
    // }

    // /**
    //  * @param array $labels
    //  */
    // public function setLabels(array $labels = []): void
    // {
    //     $this->apply(
    //         new LabelsWereUpdated($this->maintenanceId, $labels)
    //     );
    // }

    /**
     * @param MaintenanceId $maintenanceId
     * @param array         $maintenanceData
     * @param array         $customerData
     */
    private function create(
        MaintenanceId $maintenanceId,
        array $maintenanceData,
        array $customerData,
    ): void {
        $this->apply(
            new MaintenanceWasBooked(
                $maintenanceId,
                $maintenanceData,
                $customerData,
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
        $documentData = $event->getMaintenanceData();
        $this->maintenanceId = $event->getMaintenanceId();
        $this->productSku = $documentData['productSku'];
        $this->bookingDate = $documentData['bookingDate'];
        $this->warrantyCenter = $documentData['warrantyCenter'];
        $this->createdAt = $documentData['createdAt'];
        $this->customerData = CustomerBasicData::deserialize($event->getCustomerData());
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
     * @return \DateTime
     */
    public function getBookingDate(): \DateTime
    {
        return $this->bookingDate;
    }

    /**
     * @return CustomerBasicData
     */
    public function getCustomerData(): CustomerBasicData
    {
        return $this->customerData;
    }
}