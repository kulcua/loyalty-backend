<?php

declare(strict_types=1);

namespace Kulcua\Extension\Component\SuggestionBox\Domain;

use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;

/**
 * Class EventSourcedSuggestionBoxRepository.
 */
class EventSourcedSuggestionBoxRepository extends EventSourcingRepository
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
            '\Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBox',
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }
}
