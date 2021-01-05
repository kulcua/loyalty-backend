<?php

namespace Kulcua\Extension\Component\Maintenance\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class MaintenanceId.
 */
class MaintenanceId implements Identifier
{
    private $maintenanceId;

    /**
     * @param string $maintenanceId
     */
    public function __construct($maintenanceId)
    {
        Assert::string($maintenanceId);
        Assert::uuid($maintenanceId);

        $this->maintenanceId = $maintenanceId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->maintenanceId;
    }
}
