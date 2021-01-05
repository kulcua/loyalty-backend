<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Command;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class MaintenanceCommand.
 */
abstract class MaintenanceCommand
{
    /**
     * @var MaintenanceId
     */
    protected $maintenanceId;

    /**
     * MaintenanceCommand constructor.
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
