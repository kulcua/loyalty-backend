<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\SystemEvent;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class MaintenanceSystemEvent.
 */
class MaintenanceSystemEvent
{
    /**
     * @var MaintenanceId
     */
    protected $maintenanceId;

        /**
     * @var array
     */
    protected $customerData;

    /**
     * MaintenanceSystemEvent constructor.
     *
     * @param MaintenanceId $maintenanceId
     * @param array         $customerData
     */
    public function __construct(MaintenanceId $maintenanceId, array $customerData)
    {
        $this->maintenanceId = $maintenanceId;
        $this->customerData = $customerData;
    }

    /**
     * @return MaintenanceId
     */
    public function getMaintenanceId()
    {
        return $this->maintenanceId;
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
