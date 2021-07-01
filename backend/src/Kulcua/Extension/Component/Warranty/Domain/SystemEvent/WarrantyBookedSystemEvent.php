<?php

namespace Kulcua\Extension\Component\Warranty\Domain\SystemEvent;

use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;

/**
 * Class WarrantyBookedSystemEvent.
 */
class WarrantyBookedSystemEvent
{
    /**
     * @var WarrantyId
     */
    protected $warrantyId;

    /**
     * @var array
     */
    protected $warrantyData;

    /**
     * @var array
     */
    protected $customerData;

    /**
     * WarrantyBookedSystemEvent constructor.
     *
     * @param WarrantyId $warrantyId
     * @param array         $warrantyData
     * @param array         $customerData
     */
    public function __construct(WarrantyId $warrantyId, array $warrantyData, array $customerData)
    {
        $this->warrantyId = $warrantyId;
        $this->warrantyData = $warrantyData;
        $this->customerData = $customerData;
    }

    /**
     * @return WarrantyId
     */
    public function getWarrantyId(): WarrantyId
    {
        return $this->warrantyId;
    }

    /**
     * @return array
     */
    public function getWarrantyData(): array
    {
        return $this->warrantyData;
    }

    /**
     * @return array
     */
    public function getCustomerData(): array
    {
        return $this->customerData;
    }
}
