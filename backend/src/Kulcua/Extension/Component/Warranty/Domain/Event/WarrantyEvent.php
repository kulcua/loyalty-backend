<?php

namespace Kulcua\Extension\Component\Warranty\Domain\Event;

use Broadway\Serializer\Serializable;
use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;

/**
 * Class WarrantyEvent.
 */
abstract class WarrantyEvent implements Serializable
{
    /**
     * @var WarrantyId
     */
    protected $warrantyId;

    /**
     * WarrantyEvent constructor.
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

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'warrantyId' => $this->warrantyId->__toString(),
        ];
    }
}