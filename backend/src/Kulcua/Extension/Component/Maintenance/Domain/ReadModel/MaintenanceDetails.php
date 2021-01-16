<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\ReadModel;

use Broadway\ReadModel\SerializableReadModel;
use OpenLoyalty\Component\Core\Domain\ReadModel\Versionable;
use OpenLoyalty\Component\Core\Domain\ReadModel\VersionableReadModel;
use Kulcua\Extension\Component\Maintenance\Domain\CustomerId;
use Kulcua\Extension\Component\Maintenance\Domain\Model\CustomerBasicData;
use Kulcua\Extension\Component\Maintenance\Domain\Maintenance;
use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class MaintenanceDetails.
 */
class MaintenanceDetails implements SerializableReadModel, VersionableReadModel
{
    use Versionable;

    /**
     * @var MaintenanceId
     */
    protected $maintenanceId;

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
     * @var CustomerId|null
     */
    protected $customerId;

    /**
     * @var CustomerBasicData
     */
    protected $customerData;

    /**
     * MaintenanceDetails constructor.
     *
     * @param MaintenanceId $maintenanceId
     */
    public function __construct(MaintenanceId $maintenanceId)
    {
        $this->maintenanceId = $maintenanceId;
    }


    /**
     * @param array $data
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    { 
        $customerData = $data['customerData'];
        $maintenance = new self(new MaintenanceId($data['maintenanceId']));

        if (is_numeric($data['bookingDate'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($data['bookingDate']);
            $data['bookingDate'] = $tmp;
        }

        if (is_numeric($data['createdAt'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($data['createdAt']);
            $data['createdAt'] = $tmp;
        }

        $maintenance->customerData = CustomerBasicData::deserialize($customerData);

        if (!empty($data['customerId'])) {
            $maintenance->customerId = new CustomerId($data['customerId']);
        }

        $maintenance->productSku = $data['productSku'];
        $maintenance->bookingDate = $data['bookingDate'];
        $maintenance->bookingTime = $data['bookingTime'];
        $maintenance->warrantyCenter = $data['warrantyCenter'];
        $maintenance->createdAt = $data['createdAt'];
        $maintenance->active = $data['active'];

        return $maintenance;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'customerId' => $this->customerId ? (string) $this->customerId : null,
            'maintenanceId' => (string) $this->maintenanceId,
            'productSku' => $this->productSku,
            'bookingDate' => $this->getBookingDate() ? $this->getBookingDate()->getTimestamp() : null,
            'bookingTime' => $this->bookingTime,
            'warrantyCenter' => $this->warrantyCenter,
            'createdAt' => $this->getCreatedAt() ? $this->getCreatedAt()->getTimestamp() : null,
            'active' => $this->active,
            'customerData' => $this->getcustomerData()->serialize()
        ];
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
     * @return string
     */
    public function getProductSku(): string
    {
        return $this->productSku;
    }

    /**
     * @param string $productSku
     */
    public function setProductSku(string $productSku): void
    {
        $this->productSku = $productSku;
    }

    /**
     * @return \DateTime|null
     */
    public function getBookingDate(): ?\DateTime
    {
        return $this->bookingDate;
    }

    /**
     * @param \DateTime $bookingDate
     */
    public function setBookingDate(\DateTime $bookingDate): void
    {
        $this->bookingDate = $bookingDate;
    }

    /**
     * @return string $bookingTime
     */
    public function getBookingTime(string $bookingTime): ?string
    {
        return $this->bookingTime;
    }

    /**
     * @param string|null $bookingTime
     */
    public function setBookingTime(?string $bookingTime): void
    {
        $this->bookingTime = $bookingTime;
    }

    /**
     * @return string|null
     */
    public function getWarrantyCenter(): ?string
    {
        return $this->warrantyCenter;
    }

    /**
     * @param string|null $warrantyCenter
     */
    public function setWarrantyCenter(?string $warrantyCenter): void
    {
        $this->warrantyCenter = $warrantyCenter;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return CustomerId|null
     */
    public function getCustomerId(): ?CustomerId
    {
        return $this->customerId;
    }

    /**
     * @param CustomerId $customerId
     */
    public function setCustomerId(CustomerId $customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     * @return CustomerBasicData
     */
    public function getCustomerData(): CustomerBasicData
    {
        return $this->customerData;
    }

    /**
     * @param CustomerBasicData $customerData
     */
    public function setCustomerData(CustomerBasicData $customerData): void
    {
        $this->customerData = $customerData;
    }

    /**
     * @return bool
     */
    public function getActive(): array
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }
}