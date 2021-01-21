<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\Event;

use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;

/**
 * Class MaintenanceWasDeactivated.
 */
class MaintenanceWasDeactivated extends MaintenanceEvent
{
    /**
     * @var \DateTime
     */
    protected $deactivatedAt;

    public function __construct(MaintenanceId $maintenanceId)
    {
        parent::__construct($maintenanceId);
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
        $id = $data['maintenanceId'];
        $maintenance = new self(
            new MaintenanceId($id)
        );

        if (isset($data['deactivatedAt'])) {
            $date = new \DateTime();
            $date->setTimestamp($data['deactivatedAt']);
            $maintenance->setDeactivatedAt($date);
        }

        return $maintenance;
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
