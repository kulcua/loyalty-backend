<?php

namespace Kulcua\Extension\Component\Warranty\Domain\Event;

use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;
use Kulcua\Extension\Component\Warranty\Domain\Event\WarrantyEvent;

/**
 * Class WarrantyWasBooked.
 */
class WarrantyWasBooked extends WarrantyEvent
{
    /**
     * @var array
     */
    protected $warrantyData;

    /**
     * @var array
     */
    protected $customerData;

    /**
     * WarrantyEvent constructor.
     *
     * @param WarrantyId $warrantyId
     * @param array         $warrantyData
     * @param array         $customerData
     */
    public function __construct(
        WarrantyId $warrantyId,
        array $warrantyData,
        array $customerData
    ) {
        parent::__construct($warrantyId);

        if (is_numeric($warrantyData['bookingDate'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($warrantyData['bookingDate']);
            $warrantyData['bookingDate'] = $tmp;
        }

        if (is_numeric($warrantyData['createdAt'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($warrantyData['createdAt']);
            $warrantyData['createdAt'] = $tmp;
        }

        $this->warrantyData = $warrantyData;
        $this->customerData = $customerData;
    }

    /**
     * @return array
     */
    public function getWarrantyData()
    {
        return $this->warrantyData;
    }

    /**
     * @return array
     */
    public function getCustomerData()
    {
        return $this->customerData;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        $customerData = $this->customerData;

        $warrantyData = $this->warrantyData;

        return array_merge(parent::serialize(), [
            'warrantyId' => $this->warrantyId->__toString(),
            'warrantyData' => $warrantyData,
            'customerData' => $customerData,
        ]);
    }

    public static function deserialize(array $data)
    {
        $warrantyData = $data['warrantyData'];

        $customerData = $data['customerData'];

        return new self(
            new WarrantyId($data['warrantyId']),
            $warrantyData,
            $customerData
        );
    }
}
