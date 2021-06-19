<?php

namespace Kulcua\Extension\Component\Warranty\Domain;

use OpenLoyalty\Component\Core\Domain\SnapableEventSourcedAggregateRoot;
use Kulcua\Extension\Component\Warranty\Domain\Event\WarrantyWasBooked;
use Kulcua\Extension\Component\Warranty\Domain\Event\WarrantyWasUpdated;
use Kulcua\Extension\Component\Warranty\Domain\Event\WarrantyWasDeactivated;
use Kulcua\Extension\Component\Warranty\Domain\Model\CustomerBasicData;

/**
 * Class Warranty.
 */
class Warranty extends SnapableEventSourcedAggregateRoot
{
    /**
     * @var WarrantyId
     */
    protected $warrantyId;

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
        return $this->warrantyId;
    }

    /**
     * @param WarrantyId $warrantyId
     * @param array         $warrantyData
     * @param array         $customerData
     *
     * @return Warranty
     */
    public static function createWarranty(
        WarrantyId $warrantyId,
        array $warrantyData,
        array $customerData
    ): Warranty {
        $warranty = new self();
        $warranty->create(
            $warrantyId,
            $warrantyData,
            $customerData
        );

        return $warranty;
    }

    /**
     * @param WarrantyId $warrantyId
     * @param array         $warrantyData
     * @param array         $customerData
     */
    private function create(
        WarrantyId $warrantyId,
        array $warrantyData,
        array $customerData
    ): void {
        $this->apply(
            new WarrantyWasBooked(
                $warrantyId,
                $warrantyData,
                $customerData
            )
        );
    }

    //In order to find all listeners which are listening for this event, 
    //you have to find all services with tag broadway.domain.event_listener 
    //and with this method
    /**
     * @param WarrantyWasBooked $event
     */
    protected function applyWarrantyWasBooked(WarrantyWasBooked $event): void
    {
        $warrantyData = $event->getWarrantyData();
        $this->warrantyId = $event->getWarrantyId();
        $this->productSku = $warrantyData['productSku'];
        $this->bookingDate = $warrantyData['bookingDate'];
        $this->bookingTime = $warrantyData['bookingTime'];
        $this->warrantyCenter = $warrantyData['warrantyCenter'];
        $this->createdAt = $warrantyData['createdAt'];
        $this->customerData = CustomerBasicData::deserialize($event->getCustomerData());
    }

    /**
     * @param array $warrantyData
     */
    public function updateWarrantyDetails(array $warrantyData): void
    {
        $this->apply(
            new WarrantyWasUpdated($this->getWarrantyId(), $warrantyData)
        );
    }

    /**
     * Deactivate.
     */
    public function deactivate(): void
    {
        $this->apply(
            new WarrantyWasDeactivated($this->getWarrantyId())
        );
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->getWarrantyId();
    }

    /**
     * @return WarrantyId
     */
    public function getWarrantyId(): WarrantyId
    {
        return $this->warrantyId;
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
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
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
    public function getBookingTime(): string
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