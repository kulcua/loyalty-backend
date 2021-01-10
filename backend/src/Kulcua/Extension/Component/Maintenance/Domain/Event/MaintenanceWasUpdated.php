<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Event;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class MaintenanceWasUpdated.
 */
class MaintenanceWasUpdated extends MaintenanceEvent
{
    protected $maintenanceData;

    protected $customerData;

    public function __construct(MaintenanceId $maintenanceId, array $maintenanceData, array $customerData)
    {
        parent::__construct($maintenanceId);
        $this->maintenanceData = $maintenanceData;
        $this->customerData = $customerData;
    }

    public function serialize(): array
    {
        $data = $this->maintenanceData;

        return array_merge(parent::serialize(), array(
            'maintenanceData' => $this->maintenanceData,
            'customerData' => $this->customerData,
        ));
    }

    public static function deserialize(array $data)
    {
        return new self(
            new MaintenanceId($data['maintenanceId']),
            $data['maintenanceData'],
            $data['customerData']
        );
    }

    // /**
    //  * @return array
    //  */
    // public function getMaintenanceData()
    // {
    //     return $this->maintenanceData;
    // }

    // /**
    //  * @return array
    //  */
    // public function getCustomerData()
    // {
    //     return $this->customerData;
    // }
}
