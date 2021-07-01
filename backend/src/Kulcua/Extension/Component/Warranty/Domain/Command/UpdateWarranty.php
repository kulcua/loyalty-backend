<?php

namespace Kulcua\Extension\Component\Warranty\Domain\Command;

use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;

/**
 * Class UpdateWarranty.
 */
class UpdateWarranty extends WarrantyCommand
{
    /**
     * @var WarrantyId
     */
    protected $id;

    /**
     * @var array
     */
    protected $warrantyData;

    /**
     * UpdateWarranty constructor.
     *
     * @param WarrantyId $warrantyId
     * @param array   $warrantyData
     */
    public function __construct(WarrantyId $warrantyId, array $warrantyData)
    {
        parent::__construct($warrantyId);
        $this->warrantyData = $warrantyData;
    }

    /**
     * @return null|WarrantyId
     */
    public function getId(): ?WarrantyId
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getWarrantyData()
    {
        return $this->warrantyData;
    }
}
