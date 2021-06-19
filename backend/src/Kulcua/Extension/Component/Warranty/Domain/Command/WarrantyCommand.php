<?php

namespace Kulcua\Extension\Component\Warranty\Domain\Command;

use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;

/**
 * Class WarrantyCommand.
 */
abstract class WarrantyCommand
{
    /**
     * @var WarrantyId
     */
    protected $warrantyId;

    /**
     * WarrantyCommand constructor.
     *
     * @param WarrantyId $warrantyId
     */
    public function __construct(WarrantyId $warrantyId)
    {
        $this->warrantyId = $warrantyId;
    }

    /**
     * @return WarrantyId
     */
    public function getWarrantyId()
    {
        return $this->warrantyId;
    }
}
