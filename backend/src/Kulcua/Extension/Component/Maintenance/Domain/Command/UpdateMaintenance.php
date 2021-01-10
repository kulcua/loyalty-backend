<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Command;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class UpdateMaintenance.
 */
class UpdateMaintenance extends MaintenanceCommand
{
    /**
     * @var array
     */
    protected $maintenanceData;

    // /**
    //  * @var array
    //  */
    // protected $customerData;

    /**
     * UpdateMaintenance constructor.
     *
     * @param MaintenanceId $maintenanceId
     * @param array   $maintenanceData
    //  * @param array   $customerData
     */
    public function __construct(MaintenanceId $maintenanceId, array $maintenanceData)
    {
        parent::__construct($maintenanceId);
        $this->maintenanceData = $maintenanceData;
        // $this->customerData = $customerData;
    }

    /**
     * @return array
     */
    public function getMaintenanceData()
    {
        return $this->maintenanceData;
    }

    // /**
    //  * @return array
    //  */
    // public function getCustomerData()
    // {
    //     return $this->customerData;
    // }
}
