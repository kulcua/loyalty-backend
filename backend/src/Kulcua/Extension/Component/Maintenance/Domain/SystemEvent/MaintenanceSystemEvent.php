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
     * MaintenanceSystemEvent constructor.
     *
     * @param MaintenanceId $maintenanceId
     */
    public function __construct(MaintenanceId $maintenanceId)
    {
        $this->maintenanceId = $maintenanceId;
    }

    /**
     * @return MaintenanceId
     */
    public function getMaintenanceId()
    {
        return $this->maintenanceId;
    }
}
