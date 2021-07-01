<?php

namespace Kulcua\Extension\Component\Warranty\Domain\SystemEvent;

use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;

/**
 * Class WarrantySystemEvent.
 */
class WarrantySystemEvent
{
    /**
     * @var WarrantyId
     */
    protected $warrantyId;

        /**
     * @var array
     */
    protected $customerData;

    /**
     * WarrantySystemEvent constructor.
     *
     * @param WarrantyId $warrantyId
     * @param array         $customerData
     */
    public function __construct(WarrantyId $warrantyId)
    {
        $this->warrantyId = $warrantyId;
        // $this->customerData = $customerData;
    }

    /**
     * @return WarrantyId
     */
    public function getWarrantyId()
    {
        return $this->warrantyId;
    }

    /**
     * @return array
     */
    public function getCustomerData()
    {
        return $this->customerData;
    }

    /**
     * @param array $customerData
     */
    public function setCustomerData($customerData)
    {
        $this->customerData = $customerData;
    }
}
