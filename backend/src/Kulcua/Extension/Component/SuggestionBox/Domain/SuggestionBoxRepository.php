<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain;

use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Broadway\Snapshotting\Snapshot\SnapshotRepository;
use Broadway\Snapshotting\Snapshot\Trigger;
use OpenLoyalty\Component\Core\Infrastructure\Repository\SnapshottingEventSourcingRepository;

/**
 * Class SuggestionBoxRepository.
 */
class SuggestionBoxRepository extends SnapshottingEventSourcingRepository
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
