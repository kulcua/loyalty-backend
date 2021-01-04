<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\ReadModel;

use Broadway\ReadModel\SerializableReadModel;
use OpenLoyalty\Component\Core\Domain\ReadModel\Versionable;
use OpenLoyalty\Component\Core\Domain\ReadModel\VersionableReadModel;
use OpenLoyalty\Component\Maintenance\Domain\CustomerId;
use Kulcua\Extension\Component\Maintenance\Domain\Model\CustomerBasicData;
use OpenLoyalty\Component\Maintenance\Domain\Maintenance;
use OpenLoyalty\Component\Maintenance\Domain\MaintenanceId;

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
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->maintenanceId;
    }

    /**
     * {@inheritdoc}
     *
     * @return MaintenanceDetails
     */
    public static function deserialize(array $data)
    {
        $customerData = $data['customerData'];

        $maintenance = new self(new MaintenanceId($data['maintenanceId']));

        $maintenance->customerData = CustomerBasicData::deserialize($customerData);

        $maintenance->productSku = $data['productSku'];
        $maintenance->bookingDate = isset($data['bookingDate']);
        $maintenance->warrantyCenter = $data['warrantyCenter'];
        $maintenance->createdAt = $data['createdAt'];
        $maintenance->active = $data['active'];

        return $maintenance;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): array
    {
        return [
            'customerId' => $this->customerId ? (string) $this->customerId : null,
            'maintenanceId' => (string) $this->maintenanceId,
            'productSku' => $this->productSku,
            'bookingDate' => $this->bookingDate,
            'warrantyCenter' => $this->warrantyCenter,
            'createdAt' => $this->createdAt->getTimestamp(),
            'active' => $this->active,
            'customerData' => $this->customerData->serialize()
        ];
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