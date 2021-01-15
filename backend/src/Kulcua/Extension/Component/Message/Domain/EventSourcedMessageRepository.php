<?php

declare(strict_types=1);

namespace Kulcua\Extension\Component\Message\Domain;

use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;

/**
 * Class EventSourcedMessageRepository.
 */
class EventSourcedMessageRepository extends EventSourcingRepository
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
            '\Kulcua\Extension\Component\Message\Domain\Message',
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }
}
