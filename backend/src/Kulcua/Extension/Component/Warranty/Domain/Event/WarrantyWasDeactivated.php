<?php

namespace Kulcua\Extension\Component\Warranty\Domain\Event;

use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;

/**
 * Class WarrantyWasDeactivated.
 */
class WarrantyWasDeactivated extends WarrantyEvent
{
    /**
     * @var \DateTime
     */
    protected $deactivatedAt;

    public function __construct(WarrantyId $warrantyId)
    {
        parent::__construct($warrantyId);
        $this->deactivatedAt = new \DateTime();
        $this->deactivatedAt->setTimestamp(time());
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), array(
            'deactivatedAt' => $this->deactivatedAt ? $this->deactivatedAt->getTimestamp() : null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $id = $data['warrantyId'];
        $warranty = new self(
            new WarrantyId($id)
        );

        if (isset($data['deactivatedAt'])) {
            $date = new \DateTime();
            $date->setTimestamp($data['deactivatedAt']);
            $warranty->setDeactivatedAt($date);
        }

        return $warranty;
    }

    /**
     * @return \DateTime
     */
    public function getDeactivatedAt()
    {
        return $this->deactivatedAt;
    }

    /**
     * @param \DateTime $deactivatedAt
     */
    public function setDeactivatedAt($deactivatedAt)
    {
        $this->deactivatedAt = $deactivatedAt;
    }
}
