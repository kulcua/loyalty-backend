<?php

namespace Kulcua\Extension\Component\SuggestionBox\Domain\SystemEvent;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Class SuggestionBoxCreatedSystemEvent.
 */
class SuggestionBoxCreatedSystemEvent
{
    /**
     * @var SuggestionBoxId
     */
    protected $suggestionBoxId;

    /**
     * @var array
     */
    protected $suggestionBoxData;

    /**
     * SuggestionBoxBookedSystemEvent constructor.
     *
     * @param SuggestionBoxId $suggestionBoxId
     * @param array         $suggestionBoxData
     */
    public function __construct(SuggestionBoxId $suggestionBoxId, array $suggestionBoxData)
    {
        $this->suggestionBoxId = $suggestionBoxId;
        $this->suggestionBoxData = $suggestionBoxData;
    }

    /**
     * @return SuggestionBoxId
     */
    public function getSuggestionBoxId(): SuggestionBoxId
    {
        return $this->suggestionBoxId;
    }

    /**
     * @return array
     */
    public function getSuggestionBoxData(): array
    {
        return $this->suggestionBoxData;
    }
}
