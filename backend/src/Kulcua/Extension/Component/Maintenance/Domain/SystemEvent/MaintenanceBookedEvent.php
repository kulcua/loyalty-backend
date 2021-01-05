<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\SystemEvent;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class MaintenanceBookedEvent.
 */
class MaintenanceBookedEvent
{
    /**
     * @var MaintenanceId
     */
    protected $maintenanceId;

    /**
     * @var array
     */
    protected $maintenanceData;

    /**
     * @var array
     */
    protected $customerData;

    /**
     * MaintenanceBookedEvent constructor.
     *
     * @param MaintenanceId $maintenanceId
     * @param array         $maintenanceData
     * @param array         $customerData
     */
    public function __construct(MaintenanceId $maintenanceId, array $maintenanceData, array $customerData)
    {
        $this->maintenanceId = $maintenanceId;
        $this->maintenanceData = $maintenanceData;
        $this->customerData = $customerData;
    }

    /**
     * @return MaintenanceId
     */
    public function getMaintenanceId(): MaintenanceId
    {
        return $this->maintenanceId;
    }

    /**
     * @return array
     */
    public function getMaintenanceData(): array
    {
        return $this->maintenanceData;
    }

    /**
     * @return array
     */
    public function getCustomerData(): array
    {
        return $this->customerData;
    }
}
