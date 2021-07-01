<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain;

use OpenLoyalty\Component\Core\Domain\SnapableEventSourcedAggregateRoot;
use Kulcua\Extension\Component\SuggestionBox\Domain\Event\SuggestionBoxWasCreated;
use Kulcua\Extension\Component\SuggestionBox\Domain\Event\SuggestionBoxWasUpdated;
use Kulcua\Extension\Component\SuggestionBox\Domain\Event\SuggestionBoxWasDeactivated;

/**
 * Class SuggestionBox.
 */
class SuggestionBox extends SnapableEventSourcedAggregateRoot
{
    /**
     * @var SuggestionBoxId
     */
    protected $suggestionBoxId;

    /**
     * @var string
     */
    protected $senderId;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * @var string
     */
    protected $problemType;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->suggestionBoxId;
    }

    /**
     * @param SuggestionBoxId $suggestionBoxId
     * @param array         $suggestionBoxData
     *
     * @return SuggestionBox
     */
    public static function createSuggestionBox(
        SuggestionBoxId $suggestionBoxId,
        array $suggestionBoxData
    ): SuggestionBox {
        $suggestionBox = new self();
        $suggestionBox->create(
            $suggestionBoxId,
            $suggestionBoxData
        );

        return $suggestionBox;
    }

    /**
     * @param SuggestionBoxId $suggestionBoxId
     * @param array         $suggestionBoxData
     * @param array         $customerData
     */
    private function create(
        SuggestionBoxId $suggestionBoxId,
        array $suggestionBoxData
    ): void {
        $this->apply(
            new SuggestionBoxWasCreated(
                $suggestionBoxId,
                $suggestionBoxData
            )
        );
    }

    //In order to find all listeners which are listening for this event, 
    //you have to find all services with tag broadway.domain.event_listener 
    //and with this method
    /**
     * @param SuggestionBoxWasCreated $event
     */
    protected function applySuggestionBoxWasCreated(SuggestionBoxWasCreated $event): void
    {
        $suggestionBoxData = $event->getSuggestionBoxData();
        $this->suggestionBoxId = $event->getSuggestionBoxId();
        $this->senderId = $suggestionBoxData['senderId'];
        $this->senderName = $suggestionBoxData['senderName'];
        $this->problemType = $suggestionBoxData['problemType'];
        $this->description = $suggestionBoxData['description'];
        $this->active = $suggestionBoxData['active'];
        $this->timestamp = $suggestionBoxData['timestamp'];
    }

    /**
     * @param array $suggestionBoxData
     */
    public function updateSuggestionBoxDetails(array $suggestionBoxData): void
    {
        $this->apply(
            new SuggestionBoxWasUpdated($this->getSuggestionBoxId(), $suggestionBoxData)
        );
    }

    /**
     * Deactivate.
     */
    public function deactivate(): void
    {
        $this->apply(
            new SuggestionBoxWasDeactivated($this->getSuggestionBoxId())
        );
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->getSuggestionBoxId();
    }

    /**
     * @return SuggestionBoxId
     */
    public function getSuggestionBoxId(): SuggestionBoxId
    {
        return $this->suggestionBoxId;
    }


    /**
     * @return string
     */
    public function getSenderId(): string
    {
        return $this->senderId;
    }

    /**
     * @return string
     */
    public function getSenderName(): string
    {
        return $this->senderName;
    }

    /**
     * @return string
     */
    public function getSuggestionBox(): string
    {
        return $this->suggestionBox;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }
}