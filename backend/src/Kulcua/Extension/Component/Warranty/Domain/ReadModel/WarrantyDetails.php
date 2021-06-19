<?php

namespace Kulcua\Extension\Component\Warranty\Domain\ReadModel;

use Broadway\ReadModel\SerializableReadModel;
use OpenLoyalty\Component\Core\Domain\ReadModel\Versionable;
use OpenLoyalty\Component\Core\Domain\ReadModel\VersionableReadModel;
use Kulcua\Extension\Component\Warranty\Domain\CustomerId;
use Kulcua\Extension\Component\Warranty\Domain\Model\CustomerBasicData;
use Kulcua\Extension\Component\Warranty\Domain\Warranty;
use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;

/**
 * Class WarrantyDetails.
 */
class WarrantyDetails implements SerializableReadModel, VersionableReadModel
{
    use Versionable;

    /**
     * @var WarrantyId
     */
    protected $warrantyId;

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
     * WarrantyDetails constructor.
     *
     * @param WarrantyId $warrantyId
     */
    public function __construct(WarrantyId $warrantyId)
    {
        $this->warrantyId = $warrantyId;
    }


    /**
     * @param array $data
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    { 
        $customerData = $data['customerData'];
        $warranty = new self(new WarrantyId($data['warrantyId']));

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

        $warranty->customerData = CustomerBasicData::deserialize($customerData);

        if (!empty($data['customerId'])) {
            $warranty->customerId = new CustomerId($data['customerId']);
        }

        $warranty->productSku = $data['productSku'];
        $warranty->bookingDate = $data['bookingDate'];
        $warranty->bookingTime = $data['bookingTime'];
        $warranty->warrantyCenter = $data['warrantyCenter'];
        $warranty->createdAt = $data['createdAt'];
        $warranty->active = $data['active'];

        return $warranty;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'customerId' => $this->customerId ? (string) $this->customerId : null,
            'warrantyId' => (string) $this->warrantyId,
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
    public function isActive(): bool
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