<?php

declare(strict_types=1);

namespace Kulcua\Extension\Component\Warranty\Domain;

use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;

/**
 * Class EventSourcedWarrantyRepository.
 */
class EventSourcedWarrantyRepository extends EventSourcingRepository
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = array()
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            '\Kulcua\Extension\Component\Warranty\Domain\Warranty',
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }
}
