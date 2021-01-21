<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Command;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class DeactivateMaintenance.
 */
class DeactivateMaintenance extends MaintenanceCommand
{
    protected $active;

    public function __construct(MaintenanceId $maintenanceId, $active)
    {
        parent::__construct($maintenanceId);
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->active;
    }
}
