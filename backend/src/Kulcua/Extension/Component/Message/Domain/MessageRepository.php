<?php

namespace Kulcua\Extension\Component\Message\Domain;

use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Broadway\Snapshotting\Snapshot\SnapshotRepository;
use Broadway\Snapshotting\Snapshot\Trigger;
use OpenLoyalty\Component\Core\Infrastructure\Repository\SnapshottingEventSourcingRepository;

/**
 * Class MessageRepository.
 */
class MessageRepository extends SnapshottingEventSourcingRepository
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventSourcingRepository $eventSourcingRepository,
        EventStore $eventStore,
        SnapshotRepository $snapshotRepository,
        Trigger $trigger
    ) {
        parent::__construct($eventSourcingRepository, $eventStore, $snapshotRepository, $trigger);
    }
}
