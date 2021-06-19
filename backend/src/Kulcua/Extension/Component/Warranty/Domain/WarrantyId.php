<?php

namespace Kulcua\Extension\Component\Warranty\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class WarrantyId.
 */
class WarrantyId implements Identifier
{
    private $warrantyId;

    /**
     * @param string $warrantyId
     */
    public function __construct($warrantyId)
    {
        Assert::string($warrantyId);
        Assert::uuid($warrantyId);

        $this->warrantyId = $warrantyId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->warrantyId;
    }
}
