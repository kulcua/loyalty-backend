<?php

namespace Kulcua\Extension\Component\Warranty\Domain\Command;

use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;

/**
 * Class DeactivateWarranty.
 */
class DeactivateWarranty extends WarrantyCommand
{
    protected $active;

    public function __construct(WarrantyId $warrantyId, $active)
    {
        parent::__construct($warrantyId);
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->active;
    }
}
