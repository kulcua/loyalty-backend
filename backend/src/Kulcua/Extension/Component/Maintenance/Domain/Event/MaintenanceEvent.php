<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Event;

use Broadway\Serializer\Serializable;
use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class MaintenanceEvent.
 */
abstract class MaintenanceEvent implements Serializable
{
    /**
     * @var MaintenanceId
     */
    protected $maintenanceId;

    /**
     * MaintenanceEvent constructor.
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

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'maintenanceId' => $this->maintenanceId->__toString(),
        ];
    }
}