<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Event;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;
use Kulcua\Extension\Component\Maintenance\Domain\Event\MaintenanceEvent;

/**
 * Class MaintenanceWasBooked.
 */
class MaintenanceWasBooked extends MaintenanceEvent
{
    /**
     * @var array
     */
    protected $maintenanceData;

    /**
     * @var array
     */
    protected $customerData;

    /**
     * MaintenanceEvent constructor.
     *
     * @param MaintenanceId $maintenanceId
     * @param array         $maintenanceData
     * @param array         $customerData
     */
    public function __construct(
        MaintenanceId $maintenanceId,
        array $maintenanceData,
        array $customerData
    ) {
        parent::__construct($maintenanceId);

        if (is_numeric($maintenanceData['bookingDate'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($maintenanceData['bookingDate']);
            $maintenanceData['bookingDate'] = $tmp;
        }

        if (is_numeric($maintenanceData['createdAt'])) {
            $tmp = new \DateTime();
            $tmp->setTimestamp($maintenanceData['createdAt']);
            $maintenanceData['createdAt'] = $tmp;
        }

        $this->maintenanceData = $maintenanceData;
        $this->customerData = $customerData;
    }

    /**
     * @return array
     */
    public function getMaintenanceData()
    {
        return $this->maintenanceData;
    }

    /**
     * @return array
     */
    public function getCustomerData()
    {
        return $this->customerData;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        $customerData = $this->customerData;

        $maintenanceData = $this->maintenanceData;

        return array_merge(parent::serialize(), [
            'maintenanceId' => $this->maintenanceId->__toString(),
            'maintenanceData' => $maintenanceData,
            'customerData' => $customerData,
        ]);
    }

    public static function deserialize(array $data)
    {
        $maintenanceData = $data['maintenanceData'];

        $customerData = $data['customerData'];

        return new self(
            new MaintenanceId($data['maintenanceId']),
            $maintenanceData,
            $customerData
        );
    }
}
