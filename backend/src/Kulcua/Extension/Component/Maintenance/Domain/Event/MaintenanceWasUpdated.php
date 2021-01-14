<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Event;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class MaintenanceWasUpdated.
 */
class MaintenanceWasUpdated extends MaintenanceEvent
{
    protected $maintenanceData;

    public function __construct(MaintenanceId $maintenanceId, array $maintenanceData)
    {
        parent::__construct($maintenanceId);
        $this->maintenanceData = $maintenanceData;
    }

    public function serialize(): array
    {
        $data = $this->maintenanceData;

        return array_merge(parent::serialize(), array(
            'maintenanceData' => $data
        ));
    }

    public static function deserialize(array $data)
    {
        return new self(
            new MaintenanceId($data['maintenanceId']),
            $data['maintenanceData']
        );
    }

    /**
     * @return array
     */
    public function getMaintenanceData()
    {
        return $this->maintenanceData;
    }
}
