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

    // /**
    //  * @var Item[]
    //  */
    // protected $items;

    // /**
    //  * @var PosId|null
    //  */
    // protected $posId;

    /**
     * MaintenanceBookedEvent constructor.
     *
     * @param MaintenanceId $maintenanceId
     * @param array         $maintenanceData
     * @param array         $customerData
     * @param Item[]        $items
     * @param PosId|null    $posId
     */
    public function __construct(MaintenanceId $maintenanceId, array $maintenanceData, array $customerData, array
    $items, ?PosId $posId = null)
    {
        $this->maintenanceId = $maintenanceId;
        // $this->maintenanceData = $maintenanceData;
        // $this->customerData = $customerData;
        // $this->items = $items;
        // $this->posId = $posId;
    }

    /**
     * @return MaintenanceId
     */
    public function getMaintenanceId(): MaintenanceId
    {
        return $this->maintenanceId;
    }

    // /**
    //  * @return array
    //  */
    // public function getMaintenanceData(): array
    // {
    //     return $this->maintenanceData;
    // }

    // /**
    //  * @return array
    //  */
    // public function getCustomerData(): array
    // {
    //     return $this->customerData;
    // }

    // /**
    //  * @return Item[]
    //  */
    // public function getItems(): array
    // {
    //     return $this->items;
    // }

    // /**
    //  * @return PosId|null
    //  */
    // public function getPosId(): ?PosId
    // {
    //     return $this->posId;
    // }
}
