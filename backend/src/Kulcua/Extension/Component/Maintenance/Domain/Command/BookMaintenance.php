<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Command;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;
use Assert\Assertion as Assert;

/**
 * Class BookMaintenance.
 */
class BookMaintenance extends MaintenanceCommand
{
    /**
     * @var array
     */
    protected $maintenanceData;

    /**
     * @var array
     */
    protected $customerData;

    private $requiredMaintenanceFields = [
        'productSku',
        'bookingDate',
        'bookingTime',
        'warrantyCenter',
        'createdAt',
        'active',
        'description',
        'cost',
        'paymentStatus'
    ];

    private $requiredCustomerFields = [
        'name',
        'email'
    ];

    public function __construct(
        MaintenanceId $maintenanceId,
        array $maintenanceData,
        array $customerData
    ) {
        parent::__construct($maintenanceId);
        foreach ($this->requiredMaintenanceFields as $field) {
            Assert::keyExists($maintenanceData, $field);
        }

        foreach ($this->requiredCustomerFields as $field) {
            Assert::keyExists($customerData, $field);
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
}
