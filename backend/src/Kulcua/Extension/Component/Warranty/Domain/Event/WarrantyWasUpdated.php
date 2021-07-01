<?php

namespace Kulcua\Extension\Component\Warranty\Domain\Event;

use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;

/**
 * Class WarrantyWasUpdated.
 */
class WarrantyWasUpdated extends WarrantyEvent
{
    protected $warrantyData;

    public function __construct(WarrantyId $warrantyId, array $warrantyData)
    {
        parent::__construct($warrantyId);
        $this->warrantyData = $warrantyData;
    }

    public function serialize(): array
    {
        $data = $this->warrantyData;

        return array_merge(parent::serialize(), array(
            'warrantyData' => $data
        ));
    }

    public static function deserialize(array $data)
    {
        return new self(
            new WarrantyId($data['warrantyId']),
            $data['warrantyData']
        );
    }

    /**
     * @return array
     */
    public function getWarrantyData()
    {
        return $this->warrantyData;
    }
}
