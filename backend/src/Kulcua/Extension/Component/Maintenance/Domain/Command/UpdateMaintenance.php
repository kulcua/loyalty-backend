<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Command;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class UpdateMaintenance.
 */
class UpdateMaintenance extends MaintenanceCommand
{
    /**
     * @var MaintenanceId
     */
    protected $id;

    /**
     * @var array
     */
    protected $maintenanceData;

    /**
     * UpdateMaintenance constructor.
     *
     * @param MaintenanceId $maintenanceId
     * @param array   $maintenanceData
     */
    public function __construct(MaintenanceId $maintenanceId, array $maintenanceData)
    {
        parent::__construct($maintenanceId);
        $this->maintenanceData = $maintenanceData;
    }

    /**
     * @return null|MaintenanceId
     */
    public function getId(): ?MaintenanceId
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getMaintenanceData()
    {
        return $this->maintenanceData;
    }
}
